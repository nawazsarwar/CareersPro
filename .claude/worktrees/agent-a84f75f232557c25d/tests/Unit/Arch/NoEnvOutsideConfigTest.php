<?php

declare(strict_types=1);

// M00-R09 — env() is reached only from config files.
//
// `php artisan config:cache` freezes the config array and every env() call
// outside it then returns null in production. This is the check that keeps a
// working local build from becoming a broken deployed one.

it('never calls env() outside a config file', function (): void {
    $offenders = [];

    $base = dirname(__DIR__, 3);

    foreach ([$base.'/app', $base.'/routes', $base.'/database'] as $root) {
        if (! is_dir($root)) {
            continue;
        }

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            if (! $file->isFile() || $file->getExtension() !== 'php') {
                continue;
            }

            if (preg_match('/(?<![>$\w])env\s*\(/', (string) file_get_contents($file->getPathname()))) {
                $offenders[] = str_replace($base.'/', '', $file->getPathname());
            }
        }
    }

    expect($offenders)->toBe([], 'env() called outside config: '.implode(', ', $offenders));
});
