<?php

declare(strict_types=1);

namespace App\Support\Table;

/**
 * How a column is filtered. The type decides the control the filter row
 * renders and the operator the query applies, so an undeclared type is a
 * configuration error rather than a default.
 */
enum ColumnType: string
{
    case Text = 'text';
    case Exact = 'exact';
    case Number = 'number';
    case Date = 'date';
    case DateRange = 'date_range';
    case Select = 'select';
    case Boolean = 'boolean';
    case Tristate = 'tristate';
}
