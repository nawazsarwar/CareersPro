<?php

declare(strict_types=1);

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Audit\RedactProperties;
use App\Enums\AuditEventName;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// M26-R06 — the record shows that a value changed, never the value.

it('stores a changed Aadhaar as a hash and never as a value', function (): void {
    $entry = app(RecordAuditEvent::class)->handle(new AuditEvent(
        event: AuditEventName::ModelUpdated,
        properties: ['changed' => ['aadhaar_no'], 'to' => ['aadhaar_no' => '123412341234']],
    ));

    $encoded = (string) json_encode($entry->properties);

    expect($encoded)->not->toContain('123412341234')
        ->and($entry->properties['to']['aadhaar_no']['changed'])->toBeTrue()
        ->and($entry->properties['to']['aadhaar_no']['hash'])->toHaveLength(64);
});

it('redacts every declared secret, at any depth', function (): void {
    $redacted = (new RedactProperties)->handle([
        'password' => 'hunter2',
        'password_confirmation' => 'hunter2',
        'two_factor_secret' => 'JBSWY3DPEHPK3PXP',
        'recovery_code' => 'abcd-efgh',
        'otp_code' => '482913',
        'remember_token' => 'xyz',
        'nested' => ['aadhaar_no' => '123412341234', 'name' => 'Aisha Khan'],
    ]);

    $encoded = (string) json_encode($redacted);

    foreach (['hunter2', 'JBSWY3DPEHPK3PXP', 'abcd-efgh', '482913', 'xyz', '123412341234'] as $secret) {
        expect($encoded)->not->toContain($secret);
    }

    // Non-secret values in the same payload survive: a record that redacts
    // everything records nothing.
    expect($redacted['nested']['name'])->toBe('Aisha Khan');
});

// DR-024 — the SMS gateway authenticates by query parameter, so a logged URL
// is a logged credential.

it('strips the query string from a URL carrying gateway credentials', function (): void {
    $redacted = (new RedactProperties)->handle([
        'endpoint' => 'https://www.proactivesms.in/sendsms.jsp?user=amu&password=s3cret&mobiles=9999999999',
    ]);

    expect($redacted['endpoint'])->toBe('https://www.proactivesms.in/sendsms.jsp?[redacted]')
        ->and($redacted['endpoint'])->not->toContain('s3cret');
});

it('leaves an innocuous URL intact', function (): void {
    $url = 'https://amu.ac.in/advertisements/884';

    expect((new RedactProperties)->handle(['link' => $url])['link'])->toBe($url);
});

it('records a null secret as changed without inventing a hash', function (): void {
    $redacted = (new RedactProperties)->handle(['password' => null]);

    expect($redacted['password'])->toBe(['changed' => true, 'hash' => null]);
});
