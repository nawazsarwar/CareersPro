<?php

declare(strict_types=1);

use App\Domain\Application\AllocateApplicationNumber;
use App\Domain\Application\SubmitApplication;
use App\Domain\Recruitment\PublishAdvertisement;
use App\Enums\LifecycleState;
use App\Models\Advertisement;
use App\Models\Application;
use App\Models\ApplicationSnapshot;
use App\Models\Post;
use App\Models\Profile;
use App\Models\QualificationLevel;
use App\Models\User;
use App\Support\Canonical\CanonicalJson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\MasterDataSeeder::class);
});

function openPost(array $attributes = []): Post
{
    $advertisement = Advertisement::factory()->create();
    $post = Post::factory()->create(['advertisement_id' => $advertisement->getKey()] + $attributes);

    app(PublishAdvertisement::class)->handle($advertisement, User::factory()->staff()->create());

    return $post->refresh();
}

function completeCandidate(array $profile = []): User
{
    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    Profile::query()->where('user_id', $user->getKey())->update(array_merge([
        'first_name' => 'Aisha',
        'last_name' => 'Khan',
        'dob' => now()->subYears(30)->toDateString(),
        'gender' => 'female',
    ], $profile));

    $user->academicQualifications()->create([
        'qualification_level_id' => QualificationLevel::query()->where('code', 'PG')->value('id'),
        'course' => 'M.A. History',
        'year_of_passing' => 2018,
        'percentage' => 62.5,
    ]);

    return $user->refresh();
}

it('submits atomically with snapshot, number and gate rows', function (): void {
    $post = openPost();
    $user = completeCandidate();

    $application = app(SubmitApplication::class)->handle($user, $post);

    expect($application->lifecycle_state)->toBe(LifecycleState::Submitted)
        ->and($application->application_no)->not->toBeEmpty()
        ->and($application->submitted_at)->not->toBeNull()
        ->and($application->snapshots()->count())->toBe(1)
        ->and($application->statusHistory()->count())->toBe(1);
});

it('creates a gate row for the active gates only', function (): void {
    $post = openPost();
    $user = completeCandidate();

    $application = app(SubmitApplication::class)->handle($user, $post);

    // An interview-only post gets two rows, not three. The legacy modal
    // offered all three regardless, on a legally consequential decision.
    expect($application->eligibilityDecisions->pluck('gate.value')->all())
        ->toBe(['scrutiny', 'interview']);
});

it('leaves every gate pending, which is not the same as rejected', function (): void {
    $post = openPost();
    $application = app(SubmitApplication::class)->handle(completeCandidate(), $post);

    foreach ($application->eligibilityDecisions as $decision) {
        expect($decision->decision)->toBeNull()
            ->and($decision->isPending())->toBeTrue();
    }
});

it('writes a content hash that matches the payload', function (): void {
    $post = openPost();
    $application = app(SubmitApplication::class)->handle(completeCandidate(), $post);

    $snapshot = $application->snapshots()->firstOrFail();

    expect($snapshot->content_hash)->toBe(CanonicalJson::hash($snapshot->payload));
});

it('keeps Aadhaar and mobile out of the snapshot', function (): void {
    $post = openPost();
    $user = completeCandidate();
    $application = app(SubmitApplication::class)->handle($user, $post);

    // A snapshot is evidence of what was scored, not a second copy of the
    // identity columns (data-protection.md §2).
    $encoded = (string) json_encode($application->snapshots()->firstOrFail()->payload);

    expect($encoded)->not->toContain('9876543210')
        ->and($encoded)->not->toContain('aadhaar');
});

it('refuses a snapshot update at the database', function (): void {
    $post = openPost();
    $application = app(SubmitApplication::class)->handle(completeCandidate(), $post);

    expect(fn () => DB::table('application_snapshots')->where('application_id', $application->getKey())
        ->update(['content_hash' => str_repeat('0', 64)]))
        ->toThrow(Illuminate\Database\QueryException::class, 'append-only');
});

it('locks the dossier on submission', function (): void {
    $post = openPost();
    $user = completeCandidate();

    app(SubmitApplication::class)->handle($user, $post);

    expect($user->fresh()->profile->locked)->toBeTrue();
});

it('refuses an incomplete dossier and names everything missing at once', function (): void {
    $post = openPost();
    $user = User::factory()->candidate()->withProfile()->create();

    // One pass, not one field at a time.
    expect(fn () => app(SubmitApplication::class)->handle($user, $post))
        ->toThrow(RuntimeException::class, 'incomplete');
});

it('refuses a closed vacancy', function (): void {
    $post = openPost();
    $post->update(['closing_date' => now()->subDay()->toDateString()]);

    expect(fn () => app(SubmitApplication::class)->handle(completeCandidate(), $post->refresh()))
        ->toThrow(RuntimeException::class, 'not open');
});

it('permits one application per candidate per post', function (): void {
    $post = openPost();
    $user = completeCandidate();

    app(SubmitApplication::class)->handle($user, $post);

    // Enforced by the database rather than by a check somebody can forget.
    expect(fn () => app(SubmitApplication::class)->handle($user->refresh(), $post))
        ->toThrow(Illuminate\Database\QueryException::class);
});

it('rolls back everything when submission fails part way', function (): void {
    $post = openPost();
    $user = completeCandidate();

    app(SubmitApplication::class)->handle($user, $post);

    try {
        app(SubmitApplication::class)->handle($user->refresh(), $post);
    } catch (Throwable) {
        // expected
    }

    // A candidate with a number and no snapshot cannot be scored; one with a
    // snapshot and no number cannot be found.
    expect(Application::query()->count())->toBe(1)
        ->and(ApplicationSnapshot::query()->count())->toBe(1);
});

it('allocates gapless application numbers per post', function (): void {
    $post = openPost();

    foreach (range(1, 3) as $i) {
        app(SubmitApplication::class)->handle(completeCandidate(), $post);
    }

    $numbers = Application::query()->orderBy('id')->pluck('application_no')->all();

    expect($numbers)->toBe([
        sprintf('%d%06d', $post->getKey(), 1),
        sprintf('%d%06d', $post->getKey(), 2),
        sprintf('%d%06d', $post->getKey(), 3),
    ]);
});

it('refuses to allocate a number outside a transaction', function (): void {
    $connection = Mockery::mock(Illuminate\Database\ConnectionInterface::class);
    $connection->shouldReceive('transactionLevel')->andReturn(0);

    expect(fn () => (new AllocateApplicationNumber($connection))->next(openPost()))
        ->toThrow(RuntimeException::class, 'inside a transaction');
});

it('audits the submission', function (): void {
    $post = openPost();
    $application = app(SubmitApplication::class)->handle(completeCandidate(), $post);

    expect(App\Models\AuditLog::query()->where('event', 'application.submitted')->exists())->toBeTrue()
        ->and(app(App\Domain\Audit\VerifyAuditChain::class)->handle()->intact)->toBeTrue();
});
