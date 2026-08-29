<?php

declare(strict_types=1);

use App\Domain\Identity\OtpIssueResult;
use App\Domain\Notification\Sms\Gateways\ProActiveSmsGateway;
use App\Domain\Notification\Sms\SmsDispatchFailed;
use App\Domain\Notification\Sms\SmsGateway;
use App\Domain\Notification\Sms\SmsResult;
use App\Models\OtpCode;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

uses(RefreshDatabase::class);

// M03-R26 — a failed dispatch fails closed. No session, no partial login, no
// deferred verification.

it('creates no session and offers the password path when dispatch fails', function (): void {
    $this->app->bind(SmsGateway::class, fn () => new class implements SmsGateway
    {
        public function send(string $mobile, string $body): SmsResult
        {
            throw new SmsDispatchFailed('The SMS gateway could not be reached.');
        }
    });

    config(['otp.default_gateway' => 'log']);

    $this->app->bind(App\Domain\Notification\Sms\Gateways\LogSmsGateway::class, fn () => new class implements SmsGateway
    {
        public function send(string $mobile, string $body): SmsResult
        {
            throw new SmsDispatchFailed('The SMS gateway could not be reached.');
        }
    });

    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email])
        ->assertSessionHas('otp_result', OtpIssueResult::GATEWAY_FAILED);

    $this->assertGuest();

    // The code is burned rather than left usable: one the user never received
    // is one somebody else might have.
    expect(OtpCode::query()->whereNull('consumed_at')->count())->toBe(0);
});

it('records the failure in the audit log', function (): void {
    $this->app->bind(App\Domain\Notification\Sms\Gateways\LogSmsGateway::class, fn () => new class implements SmsGateway
    {
        public function send(string $mobile, string $body): SmsResult
        {
            throw new SmsDispatchFailed('nope');
        }
    });

    $user = User::factory()->candidate()->withVerifiedMobile()->create();

    $this->post(route('frontend.login.otp.request'), ['login' => $user->email]);

    expect(App\Models\AuditLog::query()->where('event', 'auth.otp.failed')->exists())->toBeTrue();
});

// M03-R27 — the gateway credentials never appear in output. DR-024's provider
// authenticates by query parameter, so a logged URL is a logged credential.

it('keeps the gateway user and password out of the exception message', function (): void {
    config([
        'services.proactive.endpoint' => 'https://sms.example.test/send',
        'services.proactive.user' => 'amu-user',
        'services.proactive.password' => 'super-secret',
    ]);

    Http::fake(['sms.example.test/*' => Http::response('nope', 500)]);

    $gateway = app(ProActiveSmsGateway::class);

    try {
        $gateway->send('9876543210', 'code 482913');
        $this->fail('The gateway should have refused.');
    } catch (SmsDispatchFailed $e) {
        expect($e->getMessage())->not->toContain('super-secret')
            ->and($e->getMessage())->not->toContain('amu-user')
            ->and($e->getMessage())->toContain('HTTP 500');
    }
});

it('treats a 200 carrying a failure body as a failure', function (): void {
    config([
        'services.proactive.endpoint' => 'https://sms.example.test/send',
        'services.proactive.user' => 'amu-user',
        'services.proactive.password' => 'super-secret',
    ]);

    // A 200 with a failure payload is how an undelivered code becomes a user
    // who cannot sign in and a system that believes it sent them one.
    Http::fake(['sms.example.test/*' => Http::response(['status' => 'failed'], 200)]);

    expect(fn () => app(ProActiveSmsGateway::class)->send('9876543210', 'code'))
        ->toThrow(SmsDispatchFailed::class, 'rejected');
});

it('refuses to send when no credentials are configured', function (): void {
    config(['services.proactive.user' => '', 'services.proactive.password' => '']);

    expect(fn () => app(ProActiveSmsGateway::class)->send('9876543210', 'code'))
        ->toThrow(SmsDispatchFailed::class, 'not configured');
});

// DR-024 — the domain never names a provider.

it('keeps every provider name inside the Gateways namespace', function (): void {
    $offenders = [];
    $root = base_path('app');

    foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $path = str_replace(base_path().'/', '', $file->getPathname());

        if (str_contains($path, 'Notification/Sms/Gateways')) {
            continue;
        }

        if (preg_match('/proactive/i', (string) file_get_contents($file->getPathname()))) {
            $offenders[] = $path;
        }
    }

    expect($offenders)->toBe([], 'Provider named outside the Gateways namespace: '.implode(', ', $offenders));
});
