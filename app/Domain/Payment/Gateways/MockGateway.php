<?php

declare(strict_types=1);

namespace App\Domain\Payment\Gateways;

use App\Domain\Payment\OrderStatusResult;
use App\Domain\Payment\PaymentGateway;
use App\Domain\Payment\ReconciliationRow;
use App\Domain\Payment\VerificationResult;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * Local development and the test suite. Settles nothing.
 *
 * Its status() is driven by a settable value rather than by the callback
 * payload, precisely so a test can assert that a callback claiming success is
 * not believed when the gateway says otherwise.
 */
final class MockGateway implements PaymentGateway
{
    private static OrderStatus $nextStatus = OrderStatus::Paid;

    private static bool $signatureValid = true;

    public static function willReport(OrderStatus $status): void
    {
        self::$nextStatus = $status;
    }

    public static function willRejectSignature(bool $reject = true): void
    {
        self::$signatureValid = ! $reject;
    }

    public static function reset(): void
    {
        self::$nextStatus = OrderStatus::Paid;
        self::$signatureValid = true;
    }

    public function name(): string
    {
        return 'mock';
    }

    /**
     * @return array<string, mixed>
     */
    public function initiate(Order $order): array
    {
        return ['redirect_url' => '/payments/mock/'.$order->order_uid];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function verify(array $payload): VerificationResult
    {
        return new VerificationResult(
            signatureValid: self::$signatureValid,
            gatewayTransactionId: is_string($payload['txn_id'] ?? null) ? $payload['txn_id'] : null,
        );
    }

    public function status(Order $order): OrderStatusResult
    {
        return new OrderStatusResult(
            status: self::$nextStatus,
            gatewayTransactionId: 'MOCK-'.$order->order_uid,
            amountPaise: $order->amount_paise,
            method: 'upi',
        );
    }

    /**
     * @return Collection<int, ReconciliationRow>
     */
    public function parseReconciliation(UploadedFile $file): Collection
    {
        $rows = new Collection;

        foreach (array_filter(explode("\n", (string) file_get_contents($file->getRealPath()))) as $index => $line) {
            if ($index === 0) {
                continue;    // header
            }

            $columns = str_getcsv($line);

            if (count($columns) < 4) {
                continue;
            }

            $rows->push(new ReconciliationRow(
                orderUid: trim((string) $columns[0]),
                gatewayTransactionId: trim((string) $columns[1]),
                status: OrderStatus::tryFrom(trim((string) $columns[2])) ?? OrderStatus::Failed,
                amountPaise: (int) $columns[3],
            ));
        }

        return $rows;
    }
}
