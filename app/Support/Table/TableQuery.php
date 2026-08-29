<?php

declare(strict_types=1);

namespace App\Support\Table;

use App\Enums\ExportFormat;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use LogicException;

/**
 * The one way a list of records reaches a screen.
 *
 * Four rules, each closing a failure the previous build shipped
 * (docs/v3/01-design/ux/data-table.md §2.1):
 *
 *   1. Only declared columns may be sorted or filtered. An undeclared column
 *      is a SQL-injection vector and an unindexed scan.
 *   2. Scope is applied FIRST. A filter narrows; it can never widen a result
 *      set beyond the actor's ownership or organisational-unit scope. The
 *      previous build had no row-level scoping at all, so any candidate could
 *      read any other candidate's dossier.
 *   3. Results are ALWAYS paginated. No unbounded set leaves this class.
 *   4. Eager-loading is declared per table, not discovered per row.
 */
final class TableQuery
{
    /**
     * @param  Builder<Model>  $query
     */
    public function __construct(
        private readonly Builder $query,
        private readonly TableConfig $config,
        private readonly Request $request,
        private readonly ?Authenticatable $user = null,
    ) {}

    /**
     * @return LengthAwarePaginator<int, Model>
     */
    public function results(): LengthAwarePaginator
    {
        // The page is read from the injected request, not from the globally
        // resolved one. Without this a TableQuery built on a request other
        // than the current one silently returns page 1 for every page.
        $page = max(1, (int) $this->request->input('page', 1));

        return $this->build()
            ->paginate($this->pageSize(), ['*'], 'page', $page)
            ->withQueryString();
    }

    /**
     * The same query the screen shows, for export.
     *
     * Deliberately reuses build(), so an export can never see a row the screen
     * would have hidden. A Dean's-office user hitting the export URL directly
     * gets their own faculty and nothing else -- asserted, not assumed.
     */
    /**
     * @return Builder<Model>
     */
    public function exportQuery(ExportFormat $format): Builder
    {
        return $this->build();
    }

    public function shouldQueue(ExportFormat $format): bool
    {
        return $this->build()->toBase()->getCountForPagination() > $format->inlineRowLimit();
    }

    /**
     * @return Builder<Model>
     */
    private function build(): Builder
    {
        $query = $this->scoped();

        if ($this->config->eager !== []) {
            $query->with($this->config->eager);
        }

        $this->applyFilters($query);
        $this->applySearch($query);
        $this->applySort($query);

        return $query;
    }

    /**
     * Rule 2. Scope before anything else.
     *
     * A model reaching this class without a `visibleTo` scope is a
     * configuration error, not a model that happens to be world-readable.
     */
    /**
     * @return Builder<Model>
     */
    private function scoped(): Builder
    {
        $query = clone $this->query;

        if (! $query->getModel()->hasNamedScope('visibleTo')) {
            throw new LogicException(sprintf(
                'Model [%s] has no visibleTo scope, so TableQuery cannot establish who may see its rows.',
                $this->config->model,
            ));
        }

        return $query->scopes(['visibleTo' => [$this->user]]);
    }

    /**
     * @param  Builder<Model>  $query
     */
    private function applyFilters(Builder $query): void
    {
        /** @var array<string, mixed> $filters */
        $filters = (array) $this->request->input('filter', []);

        foreach ($filters as $key => $value) {
            if ($value === null || $value === '' || $value === []) {
                continue;
            }

            $column = $this->config->column((string) $key);

            if ($column === null || ! $column->filterable) {
                // Rule 1: silently ignored, never passed to the database.
                continue;
            }

            $this->applyFilter($query, $column, $value);
        }
    }

    /**
     * @param  Builder<Model>  $query
     */
    private function applyFilter(Builder $query, Column $column, mixed $value): void
    {
        $target = $column->databaseColumn();

        match ($column->filterType) {
            ColumnType::Text => $query->where($target, 'like', $this->escapeLike((string) $value).'%'),
            ColumnType::Exact, ColumnType::Number => $query->where($target, '=', $value),
            ColumnType::Select => $this->applySelect($query, $target, $column, $value),
            ColumnType::Boolean => $query->where($target, '=', filter_var($value, FILTER_VALIDATE_BOOLEAN)),
            ColumnType::Tristate => $this->applyTristate($query, $target, $value),
            ColumnType::Date => $query->whereDate($target, '=', $value),
            ColumnType::DateRange => $this->applyDateRange($query, $target, $value),
            null => null,
        };
    }

    /**
     * @param  Builder<Model>  $query
     */
    private function applySelect(Builder $query, string $target, Column $column, mixed $value): void
    {
        $permitted = $column->options ?? [];
        $selected = array_values(array_intersect((array) $value, $permitted));

        if ($selected !== []) {
            $query->whereIn($target, $selected);
        }
    }

    /**
     * Three stored values, three distinct meanings: 1 eligible, 0 not
     * eligible, NULL not yet decided.
     *
     * The legacy modal rendered 0 and NULL as one merged "Pending / Not
     * Eligible" label on a legally consequential decision. They are never
     * conflated here.
     */
    /**
     * @param  Builder<Model>  $query
     */
    private function applyTristate(Builder $query, string $target, mixed $value): void
    {
        match ((string) $value) {
            'yes' => $query->where($target, '=', true),
            'no' => $query->where($target, '=', false),
            'pending' => $query->whereNull($target),
            default => null,
        };
    }

    /**
     * @param  Builder<Model>  $query
     */
    private function applyDateRange(Builder $query, string $target, mixed $value): void
    {
        $from = is_array($value) ? ($value['from'] ?? null) : null;
        $to = is_array($value) ? ($value['to'] ?? null) : null;

        if ($from !== null && $from !== '') {
            $query->whereDate($target, '>=', $from);
        }

        if ($to !== null && $to !== '') {
            $query->whereDate($target, '<=', $to);
        }
    }

    /**
     * The single search box spans every filterable text column.
     */
    /**
     * @param  Builder<Model>  $query
     */
    private function applySearch(Builder $query): void
    {
        $term = trim((string) $this->request->input('search', ''));

        if ($term === '') {
            return;
        }

        $targets = array_filter(
            $this->config->filterable(),
            static fn (Column $c): bool => $c->filterType === ColumnType::Text,
        );

        if ($targets === []) {
            return;
        }

        $query->where(function (Builder $inner) use ($targets, $term): void {
            foreach ($targets as $column) {
                $inner->orWhere($column->databaseColumn(), 'like', $this->escapeLike($term).'%');
            }
        });
    }

    /**
     * @param  Builder<Model>  $query
     */
    private function applySort(Builder $query): void
    {
        $requested = (string) $this->request->input('sort', (string) $this->config->defaultSort);
        $direction = strtolower((string) $this->request->input('direction', $this->config->defaultDirection));

        if (! in_array($direction, ['asc', 'desc'], true)) {
            $direction = $this->config->defaultDirection;
        }

        $column = $this->config->column($requested);

        if ($column === null || ! $column->sortable) {
            // Rule 1 again: an undeclared sort falls back, it does not throw at
            // the user and it never reaches the database.
            $column = $this->config->defaultSort !== null
                ? $this->config->column($this->config->defaultSort)
                : null;
        }

        if ($column !== null) {
            $query->orderBy($column->databaseColumn(), $direction);
        }

        // A deterministic tiebreak, so page 2 never repeats a row from page 1.
        $query->orderBy($query->getModel()->getQualifiedKeyName(), 'desc');
    }

    /**
     * Rule 3. Always paginated, always within the declared sizes.
     */
    private function pageSize(): int
    {
        $requested = (int) $this->request->input('per_page', $this->config->defaultPageSize);

        return in_array($requested, $this->config->pageSizes, true)
            ? $requested
            : $this->config->defaultPageSize;
    }

    private function escapeLike(string $value): string
    {
        return str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $value);
    }
}
