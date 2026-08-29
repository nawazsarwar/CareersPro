<?php

declare(strict_types=1);

use App\Domain\Recruitment\AssertWindow;
use App\Domain\Recruitment\NextWorkingDay;
use App\Domain\Recruitment\PublishAdvertisement;
use App\Enums\AdvertisementStatus;
use App\Models\Advertisement;
use App\Models\OrganisationalUnit;
use App\Models\Post;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->actor = User::factory()->staff()->create();
});

it('publishes a draft with at least one post', function (): void {
    $advertisement = Advertisement::factory()->create();
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    $published = app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    expect($published->status)->toBe(AdvertisementStatus::Published)
        ->and($published->published_at)->not->toBeNull()
        ->and($published->posts_count)->toBe(1);
});

it('refuses to publish without a post', function (): void {
    $advertisement = Advertisement::factory()->create();

    expect(fn () => app(PublishAdvertisement::class)->handle($advertisement, $this->actor))
        ->toThrow(RuntimeException::class, 'without at least one post');
});

it('refuses to publish twice', function (): void {
    $advertisement = Advertisement::factory()->create();
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    expect(fn () => app(PublishAdvertisement::class)->handle($advertisement->refresh(), $this->actor))
        ->toThrow(RuntimeException::class, 'already published');
});

// The thirty-day statutory window.

it('refuses a window shorter than thirty days', function (): void {
    $advertisement = Advertisement::factory()->create([
        'default_closing_date' => now()->addDays(10)->toDateString(),
    ]);
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    // Not a policy preference: it is the statutory minimum notice a candidate
    // is owed, and an appointment made on a shorter one is challengeable.
    expect(fn () => app(PublishAdvertisement::class)->handle($advertisement, $this->actor))
        ->toThrow(RuntimeException::class, 'statutory minimum is 30');
});

it('accepts a window of exactly thirty days', function (): void {
    $advertisement = Advertisement::factory()->create([
        'default_closing_date' => now()->addDays(30)->toDateString(),
    ]);
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    expect($advertisement->refresh()->isPublished())->toBeTrue();
});

// A closing date on a holiday moves, and the window is counted against the
// date candidates actually get.

it('moves a closing date off a Sunday', function (): void {
    $sunday = now()->addDays(40)->next(CarbonInterface::SUNDAY);

    $advertisement = Advertisement::factory()->create(['default_closing_date' => $sunday->toDateString()]);
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    expect($advertisement->refresh()->default_closing_date->isSunday())->toBeFalse();
});

it('moves a closing date off a declared holiday', function (): void {
    $holiday = now()->addDays(40)->toDateString();

    $advertisement = Advertisement::factory()->create(['default_closing_date' => $holiday]);
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor, [$holiday]);

    expect($advertisement->refresh()->default_closing_date->toDateString())->not->toBe($holiday);
});

it('never loops for ever on an impossible holiday list', function (): void {
    $everyDay = collect(range(0, 40))->map(fn (int $i): string => now()->addDays($i)->toDateString())->all();

    // Guarded rather than while(true): a list that covered every day would
    // otherwise hang the request.
    $result = app(NextWorkingDay::class)->from(now(), $everyDay);

    expect($result)->toBeInstanceOf(Carbon\CarbonImmutable::class);
});

// DR-009 — the snapshot is what the record says, for ever.

it('snapshots the organisational unit at publish', function (): void {
    $unit = OrganisationalUnit::factory()->create(['title' => 'Faculty of Arts', 'code' => 'ARTS']);

    $advertisement = Advertisement::factory()->local()->create(['organisational_unit_id' => $unit->getKey()]);
    Post::factory()->create([
        'advertisement_id' => $advertisement->getKey(),
        'organisational_unit_id' => $unit->getKey(),
    ]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    expect($advertisement->refresh()->ou_title_snapshot)->toBe('Faculty of Arts')
        ->and($advertisement->ou_path_snapshot)->toBe($unit->fresh()->path);
});

it('leaves the snapshot untouched when the unit is later renamed', function (): void {
    $unit = OrganisationalUnit::factory()->create(['title' => 'Faculty of Arts', 'code' => 'ARTS']);

    $advertisement = Advertisement::factory()->local()->create(['organisational_unit_id' => $unit->getKey()]);
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    $unit->update(['title' => 'Faculty of Arts and Humanities']);

    // The record should say what it said. A rename in 2028 must not rewrite a
    // 2026 advertisement.
    expect($advertisement->refresh()->ou_title_snapshot)->toBe('Faculty of Arts');
});

it('takes no snapshot for centrally administered recruitment', function (): void {
    $advertisement = Advertisement::factory()->create(['organisational_unit_id' => null]);
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    // General recruitment belongs to no faculty (DR-010), and ScopedPolicy
    // reads a null path as "outside every subtree".
    expect($advertisement->refresh()->ou_path_snapshot)->toBeNull();
});

it('freezes the payment gateway at publish', function (): void {
    config(['payment.default_gateway' => 'billdesk']);

    $advertisement = Advertisement::factory()->create();
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    app(PublishAdvertisement::class)->handle($advertisement, $this->actor);

    config(['payment.default_gateway' => 'razorpay']);

    // Orders created under this advertisement use the gateway that was in
    // force when candidates read the terms.
    expect($advertisement->refresh()->payment_gateway)->toBe('billdesk');
});

it('states the window rule in one place', function (): void {
    expect(AssertWindow::MINIMUM_DAYS)->toBe(30);
});
