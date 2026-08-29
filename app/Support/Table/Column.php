<?php

declare(strict_types=1);

namespace App\Support\Table;

use InvalidArgumentException;

/**
 * One declared column.
 *
 * A column that is sortable or filterable names a real database column and a
 * filter type. Nothing here is inferred: an undeclared capability is absent,
 * never defaulted, because a default is how an unindexed scan gets shipped.
 */
final readonly class Column
{
    /**
     * @param  string  $key  the attribute rendered
     * @param  string  $label  translation key for the header
     * @param  ?string  $column  the database column, when it differs from $key
     * @param  ?array<int, string>  $options  for Select, the permitted values
     */
    public function __construct(
        public string $key,
        public string $label,
        public bool $sortable = false,
        public bool $filterable = false,
        public ?ColumnType $filterType = null,
        public ?string $column = null,
        public ?array $options = null,
    ) {
        if (($this->sortable || $this->filterable) && $this->filterType === null) {
            throw new InvalidArgumentException(
                "Column [{$this->key}] is sortable or filterable but declares no filter type."
            );
        }

        if ($this->filterType === ColumnType::Select && ($this->options === null || $this->options === [])) {
            throw new InvalidArgumentException(
                "Column [{$this->key}] is a select filter but declares no options."
            );
        }
    }

    /**
     * The database column this maps to, for sorting, filtering and index checks.
     */
    public function databaseColumn(): string
    {
        return $this->column ?? $this->key;
    }
}
