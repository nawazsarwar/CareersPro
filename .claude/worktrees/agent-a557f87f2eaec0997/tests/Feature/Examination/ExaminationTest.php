<?php

declare(strict_types=1);

use App\Domain\Examination\AllocateRollNumber;
use App\Domain\Examination\AllocateSeats;
use App\Domain\Examination\AssertDownloadWindow;
use App\Domain\Examination\BuildAttendanceSheet;
use App\Domain\Examination\GenerateAdmitCard;
use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use App\Models\Application;
use App\Models\ExamCentre;
use App\Models\Post;
use App\Models\SeatAllocation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\MasterDataSeeder::class);

    $this->post = Post::factory()->create([
        'admit_card_opening_date' => now()->subDay(),
        'admit_card_closing_date' => now()->addDays(7),
        'test_date' => now()->addDays(10),
    ]);

    $this->centre = ExamCentre::query()->create([
        'code' => 'AMU-01', 'name' => 'AMU Main Campus', 'capacity' => 100, 'is_active' => true,
    ]);
});

function eligibleApplication(Post $post): Application
{
    $application = Application::factory()->create([
        'post_id' => $post->getKey(),
        'advertisement_id' => $post->advertisement_id,
        'submitted' => true,
        'paid' => true,
    ]);

    $application->eligibilityDecisions()->create([
        'gate' => EligibilityGate::Scrutiny,
        'decision' => GateDecision::Eligible,
    ]);

    return $application;
}

it('allocates gapless roll numbers per post', function (): void {
    $first = eligibleApplication($this->post);
    $second = eligibleApplication($this->post);

    DB::transaction(function () use ($first, $second): void {
        app(AllocateRollNumber::class)->handle($first);
        app(AllocateRollNumber::class)->handle($second);
    });

    expect($first->refresh()->roll_no)->toBe(sprintf('%d%05d', $this->post->getKey(), 1))
        ->and($second->refresh()->roll_no)->toBe(sprintf('%d%05d', $this->post->getKey(), 2));
});

it('never renumbers a candidate who already has a roll number', function (): void {
    $application = eligibleApplication($this->post);

    DB::transaction(fn () => app(AllocateRollNumber::class)->handle($application));
    $first = $application->refresh()->roll_no;

    DB::transaction(fn () => app(AllocateRollNumber::class)->handle($application));

    // A candidate has already been told their roll number.
    expect($application->refresh()->roll_no)->toBe($first);
});

it('refuses to allocate a roll number outside a transaction', function (): void {
    // Asserted against a stub: RefreshDatabase wraps every test in a
    // transaction, so the live connection can never report level 0 here.
    $connection = Mockery::mock(Illuminate\Database\ConnectionInterface::class);
    $connection->shouldReceive('transactionLevel')->andReturn(0);

    expect(fn () => (new AllocateRollNumber($connection))->handle(eligibleApplication($this->post)))
        ->toThrow(RuntimeException::class, 'inside a transaction');
});

it('seats every eligible candidate exactly once', function (): void {
    foreach (range(1, 5) as $i) {
        eligibleApplication($this->post);
    }

    $report = app(AllocateSeats::class)->handle($this->post);

    expect($report->placedCount())->toBe(5)
        ->and($report->unplacedCount())->toBe(0)
        ->and(SeatAllocation::query()->count())->toBe(5);
});

it('makes a double allocation impossible rather than unlikely', function (): void {
    $application = eligibleApplication($this->post);
    app(AllocateSeats::class)->handle($this->post);

    $existing = SeatAllocation::query()->firstOrFail();

    expect(fn () => SeatAllocation::query()->create([
        'application_id' => eligibleApplication($this->post)->getKey(),
        'post_id' => $this->post->getKey(),
        'exam_centre_id' => $existing->exam_centre_id,
        'room_no' => $existing->room_no,
        'seat_no' => $existing->seat_no,
        'allocation_rule' => 'preference',
    ]))->toThrow(Illuminate\Database\QueryException::class);
});

it('seats nobody who has not cleared scrutiny', function (): void {
    // Seating somebody who has not cleared scrutiny tells them they are in the
    // examination.
    $pending = Application::factory()->create([
        'post_id' => $this->post->getKey(),
        'advertisement_id' => $this->post->advertisement_id,
    ]);
    $pending->eligibilityDecisions()->create(['gate' => EligibilityGate::Scrutiny, 'decision' => null]);

    $report = app(AllocateSeats::class)->handle($this->post);

    expect($report->placedCount())->toBe(0);
});

it('honours a centre preference and records which rule applied', function (): void {
    $preferred = ExamCentre::query()->create([
        'code' => 'AMU-02', 'name' => 'City Centre', 'capacity' => 50, 'is_active' => true,
    ]);

    $application = eligibleApplication($this->post);
    $application->centrePreferences()->create(['exam_centre_id' => $preferred->getKey(), 'preference_order' => 1]);

    app(AllocateSeats::class)->handle($this->post);

    $allocation = $application->refresh()->seatAllocation;

    // Recorded so a complaint about allocation can be answered with what
    // happened rather than a guess.
    expect($allocation->exam_centre_id)->toBe((int) $preferred->getKey())
        ->and($allocation->allocation_rule)->toBe('preference');
});

it('falls back when the preferred centre is full and says so', function (): void {
    // Capacity zero means no seats, not unlimited seats.
    $full = ExamCentre::query()->create([
        'code' => 'AMU-03', 'name' => 'Small Centre', 'capacity' => 0, 'is_active' => true,
    ]);

    $application = eligibleApplication($this->post);
    $application->centrePreferences()->create(['exam_centre_id' => $full->getKey(), 'preference_order' => 1]);

    app(AllocateSeats::class)->handle($this->post);

    expect($application->refresh()->seatAllocation->allocation_rule)->toBe('fallback_any');
});

it('reports candidates it could not place rather than dropping them', function (): void {
    ExamCentre::query()->update(['is_active' => false]);

    eligibleApplication($this->post);

    $report = app(AllocateSeats::class)->handle($this->post);

    expect($report->unplacedCount())->toBe(1)
        ->and($report->unplaced[0]['reason'])->toBe('no_active_centre');
});

// The download window, whose columns the previous redesign dropped.

it('refuses an admit card before the window opens', function (): void {
    $this->post->update(['admit_card_opening_date' => now()->addDay()]);

    expect(fn () => app(AssertDownloadWindow::class)->check($this->post->refresh(), AssertDownloadWindow::ADMIT_CARD))
        ->toThrow(RuntimeException::class, 'available from');
});

it('refuses an admit card after the window closes', function (): void {
    $this->post->update(['admit_card_closing_date' => now()->subDay()]);

    // Without the window a candidate can hold an admit card for an examination
    // whose venue has since changed.
    expect(fn () => app(AssertDownloadWindow::class)->check($this->post->refresh(), AssertDownloadWindow::ADMIT_CARD))
        ->toThrow(RuntimeException::class, 'closed on');
});

it('generates an admit card with a verification code and a content hash', function (): void {
    $application = eligibleApplication($this->post);
    app(AllocateSeats::class)->handle($this->post);

    $document = app(GenerateAdmitCard::class)->handle($application->refresh(), User::factory()->staff()->create());

    // "Is this the real one" has to be answerable without trusting the paper.
    expect($document->verification_code)->toHaveLength(10)
        ->and($document->content_hash)->toHaveLength(64)
        ->and($application->refresh()->admit_card_downloaded_at)->not->toBeNull();
});

it('refuses an admit card for a candidate with no seat', function (): void {
    $application = eligibleApplication($this->post);

    expect(fn () => app(GenerateAdmitCard::class)->handle($application))
        ->toThrow(RuntimeException::class, 'no roll number');
});

it('audits every admit card as a disclosure', function (): void {
    $application = eligibleApplication($this->post);
    app(AllocateSeats::class)->handle($this->post);
    app(GenerateAdmitCard::class)->handle($application->refresh());

    expect(App\Models\AuditLog::query()->where('event', 'document.accessed')->exists())->toBeTrue();
});

// The attendance sheet.

it('orders the attendance sheet by room and seat', function (): void {
    foreach (range(1, 3) as $i) {
        eligibleApplication($this->post);
    }

    app(AllocateSeats::class)->handle($this->post);

    $sheet = app(BuildAttendanceSheet::class)->for($this->post, $this->centre);

    // An invigilator working down a differently-sorted list marks the wrong
    // person present.
    $seats = $sheet->map(static fn (Application $a): int => (int) $a->seat_no)->all();

    expect($seats)->toBe([1, 2, 3]);
});

it('filters the sheet to the interview-eligible cohort', function (): void {
    // The legacy Bulk Document screen offered "eligible only" and "interview
    // eligible only"; the previous redesign could not compute either, having
    // collapsed the three gates into four generic columns.
    $application = eligibleApplication($this->post);
    app(AllocateSeats::class)->handle($this->post);

    expect(app(BuildAttendanceSheet::class)->for($this->post, $this->centre, BuildAttendanceSheet::INTERVIEW_ELIGIBLE))
        ->toHaveCount(0);

    $application->eligibilityDecisions()->create([
        'gate' => EligibilityGate::Interview,
        'decision' => GateDecision::Eligible,
    ]);

    expect(app(BuildAttendanceSheet::class)->for($this->post, $this->centre, BuildAttendanceSheet::INTERVIEW_ELIGIBLE))
        ->toHaveCount(1);
});
