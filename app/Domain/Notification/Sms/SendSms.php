<?php

declare(strict_types=1);

namespace App\Domain\Notification\Sms;

use Illuminate\Contracts\Container\Container;
use RuntimeException;

/**
 * Resolves the configured gateway and dispatches through it.
 *
 * The domain calls this; it never names a provider.
 */
final class SendSms
{
    public function __construct(private readonly Container $container) {}

    /**
     * @throws SmsDispatchFailed
     */
    public function handle(string $mobile, string $body): SmsResult
    {
        return $this->gateway()->send($mobile, $body);
    }

    public function gateway(): SmsGateway
    {
        $name = (string) config('otp.default_gateway', 'log');

        /** @var array<string, class-string<SmsGateway>> $registry */
        $registry = (array) config('otp.gateways', []);

        if (! array_key_exists($name, $registry)) {
            throw new RuntimeException(sprintf('No SMS gateway is registered under [%s].', $name));
        }

        return $this->container->make($registry[$name]);
    }
}
