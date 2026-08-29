<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use Illuminate\Contracts\Container\Container;
use RuntimeException;

/**
 * Resolves a gateway by name from config.
 *
 * The registry lives in config/payment.php rather than here so that no vendor
 * is named anywhere in the domain (DR-018); adding a gateway is a config entry
 * and a new adapter class, and nothing else moves.
 */
final class GatewayRegistry
{
    public function __construct(private readonly Container $container) {}

    public function for(string $name): PaymentGateway
    {
        /** @var array<string, class-string<PaymentGateway>> $registry */
        $registry = (array) config('payment.gateways', []);

        if (! array_key_exists($name, $registry)) {
            throw new RuntimeException(sprintf('No payment gateway is registered under [%s].', $name));
        }

        return $this->container->make($registry[$name]);
    }
}
