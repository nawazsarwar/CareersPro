<?php

declare(strict_types=1);

// M00-R07 — no rendered translation key may resolve to itself.
//
// Before this wave 5,702 keys were referenced and neither lang/en/cruds.php
// nor lang/en/global.php existed, so every label in 260 views rendered as its
// raw key. Those views are gone; this test is what stops it recurring as the
// views are rebuilt.

it('resolves every translation key referenced in the codebase', function (): void {
    $roots = [base_path('app'), base_path('resources'), base_path('routes')];
    $unresolved = [];

    foreach ($roots as $root) {
        if (! is_dir($root)) {
            continue;
        }

        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));

        foreach ($files as $file) {
            if (! $file->isFile() || ! in_array($file->getExtension(), ['php', 'blade'], true)) {
                continue;
            }

            $contents = (string) file_get_contents($file->getPathname());

            preg_match_all(
                "/(?:__|trans)\(\s*'([a-z_]+\.[a-zA-Z0-9_.]+)'/",
                $contents,
                $matches,
            );

            foreach ($matches[1] as $key) {
                if (__($key) === $key) {
                    $unresolved[$key] = $file->getPathname();
                }
            }
        }
    }

    expect($unresolved)->toBe([], 'Unresolved translation keys: '.json_encode($unresolved));
});
