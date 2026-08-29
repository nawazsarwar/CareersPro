<?php

declare(strict_types=1);

namespace App\Enums;

enum ExportFormat: string
{
    case Csv = 'csv';
    case Excel = 'xlsx';
    case Pdf = 'pdf';

    /**
     * Rows above which the export is queued rather than streamed inline.
     *
     * A 78,232-row PDF is not a request; it is a job. The threshold lives on
     * the format because the ceiling differs per format.
     */
    public function inlineRowLimit(): int
    {
        return match ($this) {
            self::Csv => 50_000,
            self::Excel => 20_000,
            self::Pdf => 2_000,
        };
    }

    public function mimeType(): string
    {
        return match ($this) {
            self::Csv => 'text/csv',
            self::Excel => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            self::Pdf => 'application/pdf',
        };
    }
}
