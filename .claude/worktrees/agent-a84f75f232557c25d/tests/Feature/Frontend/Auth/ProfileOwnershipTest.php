<?php

declare(strict_types=1);

use App\Models\Profile;
use App\Models\User;
use App\Support\Table\Column;
use App\Support\Table\ColumnType;
use App\Support\Table\TableConfig;
use App\Support\Table\TableQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

uses(RefreshDatabase::class);

/**
 * M03-R07 — candidate A must not reach candidate B's record.
 *
 * This is defect #1 in security-model.md §2: the previous build's `frontend.`
 * route group carried no `auth` middleware at all, and its 35 controllers were
 * verbatim admin CRUD with no ownership check, so any signed-in candidate
 * could read and modify any other candidate's dossier.
 *
 * Wave 1 ships no route that exposes another candidate's record, so the
 * assertion is made where the exposure would first become possible: the
 * visibility scope every list must pass through.
 */
it('scopes a user query to the acting user', function (): void {
    $aisha = User::factory()->candidate()->create();
    $other = User::factory()->candidate()->create();

    $config = new TableConfig(
        model: User::class,
        columns: [new Column(key: 'email', label: 'email', sortable: true, filterable: true, filterType: ColumnType::Text)],
        defaultSort: 'email',
    );

    $results = (new TableQuery(User::query(), $config, Request::create('/'), $aisha))->results();

    expect($results->pluck('id')->all())->toBe([$aisha->getKey()])
        ->and($results->pluck('id')->all())->not->toContain($other->getKey());
});

it('cannot be widened past the scope by a filter', function (): void {
    $aisha = User::factory()->candidate()->create();
    $other = User::factory()->candidate()->create(['email' => 'other@example.com']);

    $config = new TableConfig(
        model: User::class,
        columns: [new Column(key: 'email', label: 'email', sortable: true, filterable: true, filterType: ColumnType::Text)],
        defaultSort: 'email',
    );

    $request = Request::create('/', 'GET', ['filter' => ['email' => 'other@example.com']]);

    expect((new TableQuery(User::query(), $config, $request, $aisha))->results()->total())->toBe(0);
});

it('keeps each profile attached to exactly one user', function (): void {
    $aisha = User::factory()->candidate()->withProfile()->create();

    expect(Profile::query()->where('user_id', $aisha->getKey())->count())->toBe(1);

    // The unique constraint is what stops a second profile being attached to
    // an account and read as though it were the first.
    expect(fn () => Profile::query()->create(['user_id' => $aisha->getKey()]))
        ->toThrow(Illuminate\Database\QueryException::class);
});

it('requires authentication for the profile mobile routes', function (): void {
    $this->post(route('frontend.profile.mobile.otp'), ['mobile' => '9876543210'])
        ->assertRedirect(route('frontend.login'));

    $this->post(route('frontend.profile.mobile.verify'), ['code' => '482913'])
        ->assertRedirect(route('frontend.login'));
});

it('requires authentication for two-factor settings', function (): void {
    $this->get(route('frontend.two-factor.index'))->assertRedirect(route('frontend.login'));
});
