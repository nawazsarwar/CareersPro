<?php

declare(strict_types=1);

namespace App\Domain\Notification\Sms\Gateways;

use App\Domain\Notification\Sms\SmsDispatchFailed;
use App\Domain\Notification\Sms\SmsGateway;
use App\Domain\Notification\Sms\SmsResult;
use Illuminate\Http\Client\Factory as HttpFactory;
use Throwable;

/**
 * ProActive, the v1 provider (DR-024).
 *
 * The single most important thing about this adapter: **the provider
 * authenticates by query parameter**. A composed URL is therefore a credential,
 * and it is never stored -- not in .env, not in system_settings, not anywhere.
 * It is assembled here, at call time, from config, and it never appears in a
 * log or an exception message.
 *
 * That is enforced three ways: the URL is built from parts rather than from a
 * stored string; failures are raised as SmsDispatchFailed carrying a message
 * this class writes, never the client's, which would embed the request URL;
 * and App\Domain\Audit\RedactProperties strips the query from any URL that
 * reaches the audit log. M03-R27 asserts it.
 */
final class ProActiveSmsGateway implements SmsGateway
{
    public function __construct(private readonly HttpFactory $http) {}

    public function send(string $mobile, string $body): SmsResult
    {
        $endpoint = (string) config('services.proactive.endpoint');
        $user = (string) config('services.proactive.user');
        $password = (string) config('services.proactive.password');
        $senderId = (string) config('services.proactive.sender_id');

        if ($user === '' || $password === '') {
            throw new SmsDispatchFailed('The SMS gateway is not configured.');
        }

        try {
            $response = $this->http
                ->timeout(10)
                // `throw: false`, so a final non-2xx comes back as a response
                // and is reported with its status. Left to throw, the client's
                // RequestException would be caught below and every failure
                // would read "could not be reached", hiding a 401 from a
                // rotated credential behind a network-sounding message.
                ->retry(2, 200, throw: false)
                ->get($endpoint, [
                    'user' => $user,
                    'password' => $password,
                    'senderid' => $senderId,
                    'mobiles' => $mobile,
                    'sms' => $body,
                    'responsein' => 'json',
                ]);
        } catch (Throwable) {
            // Deliberately not chained and not carrying the original message:
            // Illuminate\Http\Client\RequestException embeds the request URL,
            // and the request URL is the credential.
            throw new SmsDispatchFailed('The SMS gateway could not be reached.');
        }

        if ($response->failed()) {
            throw new SmsDispatchFailed(
                sprintf('The SMS gateway returned HTTP %d.', $response->status())
            );
        }

        /** @var array<string, mixed> $payload */
        $payload = (array) $response->json();

        // A 200 carrying a failure body is still a failure. Treating any 200 as
        // success is how an undelivered code becomes a user who cannot sign in
        // and a system that believes it sent them one.
        $status = strtolower((string) ($payload['status'] ?? $payload['Status'] ?? ''));

        if ($status !== '' && ! in_array($status, ['success', 'ok', 'sent'], true)) {
            throw new SmsDispatchFailed('The SMS gateway rejected the message.');
        }

        $reference = $payload['messageid'] ?? $payload['MessageId'] ?? $payload['id'] ?? null;

        return new SmsResult(
            accepted: true,
            providerReference: is_scalar($reference) ? (string) $reference : null,
        );
    }
}
