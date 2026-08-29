<?php

declare(strict_types=1);

use App\Domain\Audit\Auditable;

// Needs the application booted: app_path() and class_uses_recursive() are
// framework helpers, and the models they enumerate resolve config at load.
uses(Tests\TestCase::class);

/**
 * M26-R08 — every model emits audit events, enumerated from app/Models rather
 * than sampled.
 *
 * The trait this replaces covered 27 of 34 models and omitted User, Role,
 * Permission and ResearchPublication: the security-sensitive models were
 * precisely the unaudited ones. A sampled test would have passed on that
 * codebase.
 */
it('applies Auditable to every model', function (): void {
    $missing = [];

    foreach (glob(app_path('Models/*.php')) ?: [] as $file) {
        $class = 'App\\Models\\'.basename($file, '.php');

        if (! class_exists($class)) {
            continue;
        }

        // The audit log itself is append-only and does not audit its own
        // writes; doing so would be an unbounded recursion, not a record.
        if ($class === App\Models\AuditLog::class) {
            continue;
        }

        if (! in_array(Auditable::class, class_uses_recursive($class), true)) {
            $missing[] = $class;
        }
    }

    expect($missing)->toBe([]);
});
