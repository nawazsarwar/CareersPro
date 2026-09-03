<?php

declare(strict_types=1);

use App\Support\Table\Column;
use App\Support\Table\ColumnType;
use App\Support\Table\TableConfig;
use App\Support\Table\TableQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    Schema::create('widgets', function (Blueprint $table): void {
        $table->id();
        $table->string('code')->index();
        $table->boolean('owned')->index();
        $table->boolean('gate')->nullable()->index();
        $table->timestamps();
    });

    foreach (range(1, 40) as $i) {
        DB::table('widgets')->insert([
            'code' => sprintf('W%03d', $i),
            'owned' => $i <= 10,
            'gate' => match ($i % 3) {
                0 => true, 1 => false, default => null
            },
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
});

afterEach(function (): void {
    Schema::dropIfExists('widgets');
});

function scopedWidget(): Model
{
    return new class extends Model
    {
        protected $table = 'widgets';

        protected $guarded = [];

        public function scopeVisibleTo(Builder $query, mixed $user): Builder
        {
            return $query->where('owned', true);
        }
    };
}

function unscopedWidget(): Model
{
    return new class extends Model
    {
        protected $table = 'widgets';
    };
}

function widgetConfig(string $model): TableConfig
{
    return new TableConfig(
        model: $model,
        columns: [
            new Column(key: 'code', label: 'code', sortable: true, filterable: true, filterType: ColumnType::Text),
            new Column(key: 'gate', label: 'gate', filterable: true, filterType: ColumnType::Tristate),
        ],
        pageSizes: [5, 25],
        defaultPageSize: 5,
        defaultSort: 'code',
        defaultDirection: 'asc',
    );
}

function query(Model $model, array $input = []): TableQuery
{
    return new TableQuery($model->newQuery(), widgetConfig($model::class), Request::create('/', 'GET', $input));
}

// M00-R06 — no unbounded result set may leave TableQuery.

it('always paginates, even when no page size is requested', function (): void {
    $results = query(scopedWidget())->results();

    expect($results)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($results->perPage())->toBe(5);
});

it('ignores a page size that is not declared', function (): void {
    // A caller asking for 10,000 rows gets the declared default, not 10,000.
    expect(query(scopedWidget(), ['per_page' => 10000])->results()->perPage())->toBe(5);
});

// Scope before filters — the defect that let any candidate read any dossier.

it('applies the visibility scope before any filter', function (): void {
    expect(query(scopedWidget())->results()->total())->toBe(10);
});

it('cannot be widened past the scope by a filter', function (): void {
    // W011 exists but is outside the scope. Filtering for it returns nothing.
    $results = query(scopedWidget(), ['filter' => ['code' => 'W011']])->results();

    expect($results->total())->toBe(0);
});

it('refuses a model that declares no visibility scope', function (): void {
    expect(fn () => query(unscopedWidget())->results())
        ->toThrow(LogicException::class, 'no visibleTo scope');
});

// Rule 1 — only declared columns reach the database.

it('ignores a filter on an undeclared column', function (): void {
    expect(query(scopedWidget(), ['filter' => ['owned' => false]])->results()->total())->toBe(10);
});

it('falls back to the default sort when an undeclared column is requested', function (): void {
    $results = query(scopedWidget(), ['sort' => 'owned', 'direction' => 'asc'])->results();

    expect($results->first()->code)->toBe('W001');
});

it('keeps the three tristate values distinct', function (): void {
    // 1, 0 and NULL are three different statements about a statutory decision
    // and are never merged into one "pending or not eligible" bucket.
    $yes = query(scopedWidget(), ['filter' => ['gate' => 'yes']])->results()->total();
    $no = query(scopedWidget(), ['filter' => ['gate' => 'no']])->results()->total();
    $pending = query(scopedWidget(), ['filter' => ['gate' => 'pending']])->results()->total();

    expect($yes + $no + $pending)->toBe(10)
        ->and($yes)->toBeGreaterThan(0)
        ->and($no)->toBeGreaterThan(0)
        ->and($pending)->toBeGreaterThan(0);
});

it('escapes like wildcards in a text filter', function (): void {
    // A filter of "%" must match nothing, not every row.
    expect(query(scopedWidget(), ['filter' => ['code' => '%']])->results()->total())->toBe(0);
});

it('never repeats a row across pages', function (): void {
    $config = widgetConfig(scopedWidget()::class);
    $model = scopedWidget();

    $first = (new TableQuery($model->newQuery(), $config, Request::create('/', 'GET', ['per_page' => 5, 'page' => 1])))->results();
    $second = (new TableQuery($model->newQuery(), $config, Request::create('/', 'GET', ['per_page' => 5, 'page' => 2])))->results();

    $ids = $first->pluck('id')->merge($second->pluck('id'));

    expect($ids->unique())->toHaveCount(10);
});
