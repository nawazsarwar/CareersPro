<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Transaction;
use Carbon\CarbonImmutable;
use Illuminate\Database\ConnectionInterface;
use RuntimeException;

/**
 * A callback is a hint, never a fact.
 *
 * Every callback triggers a server-to-server status() check before an order is
 * marked paid, so a forged callback -- or a replayed one, or one whose
 * signature is right but whose claim is stale -- cannot mark anything paid.
 * The signature check is necessary and is not sufficient.
 */
final class HandleCallback
{
    public function __construct(
        private readonly ConnectionInterface $connection,
        private readonly GatewayRegistry $gateways,
        private readonly RecordAuditEvent $audit,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handle(Order $order, array $payload): Order
    {
        $gateway = $this->gateways->for($order->gateway);

        $verification = $gateway->verify($payload);

        if (! $verification->signatureValid) {
            $this->auditRejection($order, 'signature_invalid');

            throw new RuntimeException('That payment callback could not be verified.');
        }

        // The authoritative question, asked of the gateway directly rather
        // than read from what the browser posted.
        $status = $gateway->status($order);

        return $this->connection->transaction(function () use ($order, $status): Order {
            if ($status->gatewayTransactionId !== null) {
                Transaction::query()->updateOrCreate(
                    ['gateway_txn_id' => $status->gatewayTransactionId],
                    [
                        'order_id' => $order->getKey(),
                        'status' => $status->status->value,
                        'amount_paise' => $status->amountPaise ?? $order->amount_paise,
                        'method' => $status->method,
                        // Whatever the gateway returned, minus anything
                        // instrument-shaped: no card data is stored.
                        'gateway_payload' => $this->scrub($status->raw),
                        'occurred_at' => CarbonImmutable::now(),
                    ],
                );
            }

            $order->forceFill([
                'status' => $this->resolveStatus($order, $status->status),
                'pg_ref_no' => $status->gatewayTransactionId ?? $order->pg_ref_no,
                'settled_at' => $status->status->isSettled() ? CarbonImmutable::now() : null,
            ])->save();

            if ($order->status->grantsAccess()) {
                // Payment sets `paid` and `order_id` and nothing else. It never
                // touches the snapshot: what was scored must not change
                // because money moved.
                $order->application()->update([
                    'paid' => true,
                    'order_id' => $order->getKey(),
                ]);
            }

            $this->audit->handle(new AuditEvent(
                event: AuditEventName::PaymentSettled,
                properties: ['status' => $order->status->value, 'order_uid' => $order->order_uid],
                subject: $order,
                actorId: (int) $order->user_id,
            ));

            return $order->refresh();
        });
    }

    /**
     * A second successful settlement of an already-paid order is a double
     * payment, and it is recorded as one rather than overwriting the first.
     */
    private function resolveStatus(Order $order, OrderStatus $incoming): OrderStatus
    {
        if ($order->status->grantsAccess() && $incoming === OrderStatus::Paid) {
            return OrderStatus::DoublePayment;
        }

        return $incoming;
    }

    /**
     * @param  array<string, mixed>  $raw
     * @return array<string, mixed>
     */
    private function scrub(array $raw): array
    {
        foreach (['card', 'card_number', 'cardnumber', 'pan', 'cvv', 'expiry'] as $key) {
            unset($raw[$key]);
        }

        return $raw;
    }

    private function auditRejection(Order $order, string $reason): void
    {
        $this->audit->handle(new AuditEvent(
            event: AuditEventName::PaymentCallbackRejected,
            properties: ['reason' => $reason, 'order_uid' => $order->order_uid],
            subject: $order,
        ));
    }
}
