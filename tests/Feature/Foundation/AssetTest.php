<?php

declare(strict_types=1);

// M00-R02 — DR-021 is Blade, Alpine and Tailwind. Neither jQuery nor
// DataTables may return.
//
// The previous build emitted `class="datatable"` from 33 admin lists into a
// layout that loaded neither library, so all 33 rendered an empty table. The
// check runs against the built output, not the source, because a transitive
// dependency is exactly how one of them would come back.

it('ships no jQuery or DataTables in the built assets', function (): void {
    $dir = public_path('build/assets');

    if (! is_dir($dir)) {
        $this->markTestSkipped('No build present. CI runs `npm run build` before this test.');
    }

    $offenders = [];

    foreach (glob($dir.'/*.{js,css}', GLOB_BRACE) ?: [] as $file) {
        $contents = (string) file_get_contents($file);

        if (preg_match('/\bjquery\b|\bdataTables\b/i', $contents)) {
            $offenders[] = basename($file);
        }
    }

    expect($offenders)->toBe([], 'jQuery or DataTables found in: '.implode(', ', $offenders));
});
