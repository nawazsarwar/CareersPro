<?php

declare(strict_types=1);

use App\Support\Table\Column;
use App\Support\Table\ColumnType;
use App\Support\Table\TableConfig;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    Schema::create('widgets', function (Blueprint $table): void {
        $table->id();
        $table->string('code')->index();
        $table->string('note');            // deliberately unindexed
        $table->timestamps();
    });
});

afterEach(function (): void {
    Schema::dropIfExists('widgets');
});

function widgetModel(): string
{
    return new class extends Model
    {
        protected $table = 'widgets';
    }::class;
}

// M00-R04 — a misconfigured table fails at boot, not at request time.

it('rejects a sortable column that carries no index', function (): void {
    expect(fn () => new TableConfig(
        model: widgetModel(),
        columns: [new Column(key: 'note', label: 'note', sortable: true, filterType: ColumnType::Text)],
    ))->toThrow(LogicException::class, 'carries no index');
});

it('rejects a filterable column that does not exist on the table', function (): void {
    expect(fn () => new TableConfig(
        model: widgetModel(),
        columns: [new Column(key: 'ghost', label: 'ghost', filterable: true, filterType: ColumnType::Text)],
    ))->toThrow(LogicException::class, 'does not exist');
});

it('rejects a sortable column that declares no filter type', function (): void {
    expect(fn () => new Column(key: 'code', label: 'code', sortable: true))
        ->toThrow(InvalidArgumentException::class, 'declares no filter type');
});

it('rejects a select filter with no options', function (): void {
    expect(fn () => new Column(key: 'code', label: 'code', filterable: true, filterType: ColumnType::Select))
        ->toThrow(InvalidArgumentException::class, 'declares no options');
});

it('rejects a default sort on a column that is not sortable', function (): void {
    expect(fn () => new TableConfig(
        model: widgetModel(),
        columns: [new Column(key: 'code', label: 'code', filterable: true, filterType: ColumnType::Text)],
        defaultSort: 'code',
    ))->toThrow(LogicException::class, 'not a declared sortable column');
});

it('rejects a default page size that is not among the declared sizes', function (): void {
    expect(fn () => new TableConfig(
        model: widgetModel(),
        columns: [new Column(key: 'code', label: 'code')],
        pageSizes: [25, 50],
        defaultPageSize: 100,
    ))->toThrow(LogicException::class, 'not among its declared sizes');
});

it('accepts a correctly declared table', function (): void {
    $config = new TableConfig(
        model: widgetModel(),
        columns: [
            new Column(key: 'code', label: 'code', sortable: true, filterable: true, filterType: ColumnType::Text),
            new Column(key: 'note', label: 'note'),
        ],
        defaultSort: 'code',
    );

    expect($config->sortable())->toHaveCount(1)
        ->and($config->filterable())->toHaveCount(1)
        ->and($config->column('note'))->not->toBeNull();
});
