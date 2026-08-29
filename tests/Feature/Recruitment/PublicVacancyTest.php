<?php

declare(strict_types=1);

use App\Domain\Recruitment\IssueCorrigendum;
use App\Domain\Recruitment\PublishAdvertisement;
use App\Enums\AdvertisementStatus;
use App\Models\Advertisement;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(Database\Seeders\MasterDataSeeder::class);
    $this->actor = User::factory()->staff()->create();
});

function publishedAdvertisement(array $attributes = []): Advertisement
{
    $advertisement = Advertisement::factory()->create($attributes);
    Post::factory()->create(['advertisement_id' => $advertisement->getKey()]);

    return app(PublishAdvertisement::class)->handle($advertisement, User::factory()->staff()->create());
}

it('lists published vacancies without requiring an account', function (): void {
    $advertisement = publishedAdvertisement();

    // A vacancy notice is a public document. Requiring an account to read one
    // would exclude the people it is for.
    $this->get(route('frontend.vacancies.index'))
        ->assertOk()
        ->assertSee($advertisement->posts->first()->title);
});

// A draft must not leak, not even to a guessed slug.

it('hides a draft advertisement from the listing', function (): void {
    $draft = Advertisement::factory()->create();
    $post = Post::factory()->create(['advertisement_id' => $draft->getKey()]);

    $this->get(route('frontend.vacancies.index'))->assertOk()->assertDontSee($post->title);
});

it('returns 404 for a draft reached by its slug', function (): void {
    $draft = Advertisement::factory()->create();
    $post = Post::factory()->create(['advertisement_id' => $draft->getKey()]);

    // An unpublished vacancy that leaks is an advantage handed to whoever
    // found it.
    $this->get(route('frontend.vacancies.show', $post->slug))->assertNotFound();
    $this->get(route('frontend.advertisements.show', $draft->slug))->assertNotFound();
});

it('hides a withdrawn post', function (): void {
    $advertisement = publishedAdvertisement();
    $post = $advertisement->posts->first();
    $post->update(['withdrawn' => true]);

    $this->get(route('frontend.vacancies.index'))->assertOk()->assertDontSee($post->title);
});

it('keeps a closed advertisement readable but not open', function (): void {
    $advertisement = publishedAdvertisement();
    $advertisement->update(['status' => AdvertisementStatus::Closed]);

    // Candidates need to see what they applied to.
    $this->get(route('frontend.vacancies.show', $advertisement->posts->first()->slug))
        ->assertOk()
        ->assertSee(__('recruitment.closed_notice'));
});

it('filters to open vacancies only', function (): void {
    $open = publishedAdvertisement();
    $closed = publishedAdvertisement();
    $closed->posts->first()->update(['closing_date' => now()->subDay()->toDateString()]);

    $this->get(route('frontend.vacancies.index', ['open_only' => 1]))
        ->assertOk()
        ->assertSee($open->posts->first()->title)
        ->assertDontSee($closed->posts->first()->title);
});

it('escapes wildcards in the search term', function (): void {
    publishedAdvertisement();

    // A search for "%" must match nothing, not every row.
    $this->get(route('frontend.vacancies.index', ['q' => '%']))
        ->assertOk()
        ->assertSee(__('recruitment.none_found'));
});

it('names the submission venue from the post type', function (): void {
    $advertisement = publishedAdvertisement();

    $this->get(route('frontend.vacancies.show', $advertisement->posts->first()->slug))
        ->assertOk()
        ->assertSee(__('recruitment.hardcopy'));
});

// Corrigenda are objects, not edits.

it('publishes a corrigendum and shows it on the vacancy', function (): void {
    $advertisement = publishedAdvertisement();

    $corrigendum = app(IssueCorrigendum::class)->handle(
        $advertisement,
        $this->actor,
        'The closing date is extended by fourteen days.',
        ['default_closing_date' => now()->addDays(60)->toDateString()],
    );

    expect($corrigendum->corrigendum_no)->toBe(1)
        ->and($corrigendum->changes['from'])->toHaveKey('default_closing_date');

    $this->get(route('frontend.vacancies.show', $advertisement->posts->first()->slug))
        ->assertOk()
        ->assertSee('The closing date is extended by fourteen days.');
});

it('numbers corrigenda in sequence', function (): void {
    $advertisement = publishedAdvertisement();

    app(IssueCorrigendum::class)->handle($advertisement, $this->actor, 'First.');
    $second = app(IssueCorrigendum::class)->handle($advertisement->refresh(), $this->actor, 'Second.');

    expect($second->corrigendum_no)->toBe(2);
});

it('refuses a corrigendum on an unpublished advertisement', function (): void {
    $draft = Advertisement::factory()->create();

    expect(fn () => app(IssueCorrigendum::class)->handle($draft, $this->actor, 'Nope.'))
        ->toThrow(RuntimeException::class, 'published advertisement only');
});

it('refuses a corrigendum that would rewrite eligibility', function (): void {
    $advertisement = publishedAdvertisement();

    // A corrigendum that could rewrite eligibility would be an edit wearing a
    // different name, and candidates who applied under the original terms
    // would have a grievance the system could not answer.
    expect(fn () => app(IssueCorrigendum::class)->handle(
        $advertisement,
        $this->actor,
        'Changing the rules.',
        ['appointment_nature' => 'local'],
    ))->toThrow(RuntimeException::class, 'cannot change');
});

it('moves a corrigendum closing date off a holiday too', function (): void {
    $advertisement = publishedAdvertisement();
    $holiday = now()->addDays(60)->toDateString();

    app(IssueCorrigendum::class)->handle(
        $advertisement,
        $this->actor,
        'Extended.',
        ['default_closing_date' => $holiday],
        [$holiday],
    );

    expect($advertisement->refresh()->default_closing_date->toDateString())->not->toBe($holiday);
});

it('records the active gates from the selection method, not all three', function (): void {
    $advertisement = publishedAdvertisement();
    $post = $advertisement->posts->first();

    expect($post->activeGates())->toBe(['scrutiny', 'interview'])
        ->and($post->activeGates())->not->toContain('written_test');
});
