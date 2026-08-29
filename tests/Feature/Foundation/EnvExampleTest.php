<?php

declare(strict_types=1);

// M00-R10 — every gateway credential key is present in .env.example and empty.
//
// DR-024's gateway authenticates by query parameter, so a credential that
// leaks into a committed file leaks into every clone of the repository.

it('declares every credential key with an empty value', function (): void {
    $lines = file(base_path('.env.example'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $env = [];

    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || ! str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $env[trim($key)] = trim($value);
    }

    $credentials = ['SMS_PROACTIVE_USER', 'SMS_PROACTIVE_PASSWORD'];

    foreach ($credentials as $key) {
        expect($env)->toHaveKey($key)
            ->and($env[$key])->toBe('', "{$key} must be present and empty in .env.example.");
    }
});

it('declares the one-time-code settings M03 depends on', function (): void {
    $contents = (string) file_get_contents(base_path('.env.example'));

    foreach (['OTP_VALID_MINUTES', 'OTP_DELAY_MINUTES', 'AUTH_OTP_MAX_PER_HOUR', 'SMS_GATEWAY'] as $key) {
        expect($contents)->toContain($key);
    }
});
