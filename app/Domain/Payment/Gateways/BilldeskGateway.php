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
 * BillDesk (DR-018).
 *
 * Its message format is pipe-delimited with a trailing checksum, and its
 * settlement file uses a different column order and a different status
 * vocabulary from Razorpay's. Both are mapped to the same ReconciliationRow,
 * which is the whole point of the adapter boundary: the reconciler reads one
 * shape and has never heard of either vendor.
 */
final class BilldeskGateway implements PaymentGateway
{
    public function name(): string
    {
        return 'billdesk';
    }

    /**
     * @return array<string, mixed>
     */
    public function initiate(Order $order): array
    {
        $message = implode('|', [
            (string) config('payment.billdesk.merchant_id'),
            $order->order_uid,
            number_format($order->amount_paise / 100, 2, '.', ''),
        ]);

        return ['msg' => $message.'|'.$this->checksum($message)];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function verify(array $payload): VerificationResult
    {
        $message = (string) ($payload['msg'] ?? '');

        if ($message === '') {
            return new VerificationResult(false, reason: 'incomplete_payload');
        }

        $parts = explode('|', $message);
        $received = array_pop($parts);
        $body = implode('|', $parts);

        return new VerificationResult(
            signatureValid: hash_equals($this->checksum($body), (string) $received),
            gatewayTransactionId: $parts[2] ?? null,
        );
    }

    public function status(Order $order): OrderStatusResult
    {
        // BillDesk's status query is a signed message over the same channel.
        // Until credentials exist the adapter reports what it knows rather
        // than guessing: unknown is not failed, and marking an order failed
        // because a query could not be made would refund a candidate who paid.
        return new OrderStatusResult(status: $order->status);
    }

    /**
     * @return Collection<int, ReconciliationRow>
     */
    public function parseReconciliation(UploadedFile $file): Collection
    {
        $rows = new Collection;
        $handle = fopen($file->getRealPath(), 'rb');

        if ($handle === false) {
            return $rows;
        }

        fgetcsv($handle, escape: '\\');

        while (($columns = fgetcsv($handle, escape: '\\')) !== false) {
            if ($columns === [null] || count($columns) < 5) {
                continue;
            }

            // BillDesk's own order: reference, transaction id, amount, then
            // status -- amount and status transposed against Razorpay's.
            $rows->push(new ReconciliationRow(
                orderUid: trim((string) $columns[1]),
                gatewayTransactionId: trim((string) $columns[2]),
                status: strtoupper(trim((string) $columns[4])) === 'SUCCESS' ? OrderStatus::Paid : OrderStatus::Failed,
                amountPaise: (int) round(((float) $columns[3]) * 100),
            ));
        }

        fclose($handle);

        return $rows;
    }

    private function checksum(string $message): string
    {
        return strtoupper(hash_hmac('sha256', $message, (string) config('payment.billdesk.checksum_key')));
    }
}
