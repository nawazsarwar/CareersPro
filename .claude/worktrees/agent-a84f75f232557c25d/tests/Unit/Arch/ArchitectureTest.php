<?php

declare(strict_types=1);

// Standing architectural rules from docs/v3/01-design/engineering-standards.md.
// They are cheap here and expensive to retrofit once violated across a wave.

arch('declares strict types everywhere')
    ->expect('App')
    ->toUseStrictTypes();

arch('never leaves a debug helper behind')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r', 'die'])
    ->not->toBeUsed();

arch('keeps TOTP cryptography inside the identity domain')
    // DR-022: pragmarx/google2fa and bacon/bacon-qr-code are adapters at the
    // edge, not a dependency the rest of the application may reach for.
    ->expect(['PragmaRX\Google2FA', 'BaconQrCode'])
    ->toOnlyBeUsedIn('App\Domain\Identity\SecondFactor\Totp');

arch('keeps support helpers free of framework HTTP concerns')
    ->expect('App\Support\Canonical')
    ->not->toUse(['Illuminate\Http\Request', 'Illuminate\Support\Facades\Auth']);
