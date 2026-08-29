<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Enums\OrderStatus;
use App\Models\Application;
use App\Models\Order;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * Idempotent order creation -- the double-deduction fix.
 *
 * Calling this twice for the same (user, post, attempt) returns the SAME
 * order. CU-Chayan's users report being charged twice at deadline hours, when
 * an impatient candidate double-submits or a slow gateway response prompts a
 * retry; the legacy portal's 45,280 orders against zero transaction rows say
 * the same thing in different words.
 *
 * The key is a hash rather than a lookup on (user, post) because an attempt
 * counter has to be part of it: a genuinely new attempt after a failure must
 * produce a new order, and only the caller knows which case it is in.
 */
final class CreateOrder
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly ComputeFee $fee,
    ) {}

    public function handle(Application $application, int $attempt = 1): Order
    {
        $key = $this->idempotencyKey($application, $attempt);

        $existing = Order::query()->where('idempotency_key', $key)->first();

        if ($existing !== null) {
            return $existing;
        }

        $amount = $this->fee->for($application->user, $application->post);

        if ($amount->isZero()) {
            // An order for zero would have to be settled and then reconciled
            // against a payment that never happens. A candidate who owes
            // nothing is simply marked paid.
            throw new RuntimeException('No fee is payable for this application.');
        }

        return $this->connection->transaction(function () use ($application, $key, $amount): Order {
            // firstOrCreate under the unique index, so two concurrent requests
            // resolve to one row rather than one succeeding and one erroring.
            return Order::query()->firstOrCreate(
                ['idempotency_key' => $key],
                [
                    'order_uid' => (string) Str::uuid(),
                    'application_id' => $application->getKey(),
                    'user_id' => $application->user_id,
                    'amount_paise' => $amount->paise,
                    // Copied from the advertisement, which froze it at
                    // publish: an order settles through the gateway that was
                    // in force when the candidate read the terms.
                    'gateway' => $application->post->advertisement->payment_gateway
                        ?? $this->defaultGateway(),
                    'status' => OrderStatus::Created,
                ],
            );
        });
    }

    private function defaultGateway(): string
    {
        $gateway = config('payment.default_gateway');

        if (! is_string($gateway) || $gateway === '') {
            throw new RuntimeException('No default payment gateway is configured.');
        }

        return $gateway;
    }

    public function idempotencyKey(Application $application, int $attempt): string
    {
        return hash('sha256', sprintf(
            '%d|%d|%d',
            (int) $application->user_id,
            (int) $application->post_id,
            $attempt,
        ));
    }
}
