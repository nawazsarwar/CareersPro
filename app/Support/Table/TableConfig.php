<?php

declare(strict_types=1);

namespace App\Support\Table;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use LogicException;

/**
 * The declared shape of one table.
 *
 * Self-validating on construction: a table whose configuration is wrong fails
 * when it is built, not when a user hits the page (M00 §5, M00-R04). The three
 * checks below each correspond to a failure the previous build shipped -- a
 * sort on a column that does not exist, a filter on an unindexed column at
 * 78,232 rows, and 100 rows times nine unloaded relations.
 */
final class TableConfig
{
    /**
     * @param  class-string<Model>  $model
     * @param  array<int, Column>  $columns
     * @param  array<int, string>  $eager  relations loaded for every row
     * @param  array<int, int>  $pageSizes
     */
    public function __construct(
        public readonly string $model,
        public readonly array $columns,
        public readonly array $eager = [],
        public readonly array $pageSizes = [25, 50, 100],
        public readonly int $defaultPageSize = 100,
        public readonly ?string $defaultSort = null,
        public readonly string $defaultDirection = 'desc',
    ) {
        $this->assertColumnsExist();
        $this->assertSortableAndFilterableAreIndexed();
        $this->assertDefaultsAreDeclared();
    }

    public function column(string $key): ?Column
    {
        foreach ($this->columns as $column) {
            if ($column->key === $key) {
                return $column;
            }
        }

        return null;
    }

    /**
     * @return array<int, Column>
     */
    public function sortable(): array
    {
        return array_values(array_filter($this->columns, static fn (Column $c): bool => $c->sortable));
    }

    /**
     * @return array<int, Column>
     */
    public function filterable(): array
    {
        return array_values(array_filter($this->columns, static fn (Column $c): bool => $c->filterable));
    }

    private function table(): string
    {
        /** @var Model $model */
        $model = new $this->model;

        return $model->getTable();
    }

    private function assertColumnsExist(): void
    {
        $table = $this->table();

        foreach ($this->columns as $column) {
            if (! $column->sortable && ! $column->filterable) {
                continue;
            }

            if (! Schema::hasColumn($table, $column->databaseColumn())) {
                throw new LogicException(sprintf(
                    'Table [%s] declares column [%s] as sortable or filterable, but [%s.%s] does not exist.',
                    $this->model,
                    $column->key,
                    $table,
                    $column->databaseColumn(),
                ));
            }
        }
    }

    /**
     * Every sortable or filterable column must carry an index.
     *
     * At 78,232 rows an unindexed sort is a thirty-second page. This is the
     * check that keeps that from reaching production, and it runs at boot
     * rather than in a review comment.
     */
    private function assertSortableAndFilterableAreIndexed(): void
    {
        $table = $this->table();
        $indexed = $this->indexedColumns($table);

        foreach ($this->columns as $column) {
            if (! $column->sortable && ! $column->filterable) {
                continue;
            }

            if (! in_array($column->databaseColumn(), $indexed, true)) {
                throw new LogicException(sprintf(
                    'Table [%s] declares [%s] as sortable or filterable, but [%s.%s] carries no index. '
                    .'Add one in a migration, or drop the capability from the column.',
                    $this->model,
                    $column->key,
                    $table,
                    $column->databaseColumn(),
                ));
            }
        }
    }

    private function assertDefaultsAreDeclared(): void
    {
        if (! in_array($this->defaultPageSize, $this->pageSizes, true)) {
            throw new LogicException(sprintf(
                'Table [%s] defaults to a page size of %d, which is not among its declared sizes.',
                $this->model,
                $this->defaultPageSize,
            ));
        }

        if ($this->defaultSort !== null) {
            $column = $this->column($this->defaultSort);

            if ($column === null || ! $column->sortable) {
                throw new LogicException(sprintf(
                    'Table [%s] sorts by [%s] by default, which is not a declared sortable column.',
                    $this->model,
                    $this->defaultSort,
                ));
            }
        }

        if (! in_array($this->defaultDirection, ['asc', 'desc'], true)) {
            throw new LogicException(sprintf(
                'Table [%s] declares an invalid default sort direction [%s].',
                $this->model,
                $this->defaultDirection,
            ));
        }
    }

    /**
     * The first column of every index on the table.
     *
     * Only the leading column of a composite index can serve an independent
     * sort or filter, which is why this reads the first column rather than
     * every column in the index.
     *
     * @return array<int, string>
     */
    private function indexedColumns(string $table): array
    {
        $columns = [];

        foreach (Schema::getIndexes($table) as $index) {
            if (isset($index['columns'][0])) {
                $columns[] = $index['columns'][0];
            }
        }

        return array_values(array_unique($columns));
    }
}
