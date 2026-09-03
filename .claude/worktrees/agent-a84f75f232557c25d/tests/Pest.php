<?php

declare(strict_types=1);

use Tests\TestCase;

uses(TestCase::class)->in('Feature');

/*
 * Unit tests that need the application container bind it per file with
 * `uses(TestCase::class)`. Binding the whole Unit directory here would stop
 * the pure ones -- CanonicalJson, the architecture rules -- from running
 * without a booted application, which is the point of keeping them separate.
 */

/**
 * The immutability triggers are the whole point of M26-R02 and M26-R03, so a
 * test that has to simulate tampering drops them first. It lives here rather
 * than in a test file so the intent is obvious wherever it is used.
 */
function dropAuditGuards(): void
{
    Illuminate\Support\Facades\DB::statement('DROP TRIGGER IF EXISTS audit_logs_no_update');
    Illuminate\Support\Facades\DB::statement('DROP TRIGGER IF EXISTS audit_logs_no_delete');
}
