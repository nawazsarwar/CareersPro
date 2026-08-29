<?php

declare(strict_types=1);

namespace App\Domain\Payment\Gateways;

use App\Domain\Payment\OrderStatusResult;
use App\Domain\Payment\PaymentGateway;
use App\Domain\Payment\ReconciliationRow;
use App\Domain\Payment\VerificationResult;
use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Throwable;

/**
 * Razorpay (DR-018).
 *
 * Signature verification is HMAC-SHA256 over "order_id|payment_id" with the
 * key secret, compared with hash_equals -- a timing-safe comparison, because a
 * string comparison on a signature is a side channel.
 */
final class RazorpayGateway implements PaymentGateway
{
    public function __construct(private readonly HttpFactory $http) {}

    public function name(): string
    {
        return 'razorpay';
    }

    /**
     * @return array<string, mixed>
     */
    public function initiate(Order $order): array
    {
        return [
            'key' => (string) config('payment.razorpay.key_id'),
            'amount' => $order->amount_paise,
            'currency' => $order->currency,
            'receipt' => $order->order_uid,
        ];
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function verify(array $payload): VerificationResult
    {
        $orderId = (string) ($payload['razorpay_order_id'] ?? '');
        $paymentId = (string) ($payload['razorpay_payment_id'] ?? '');
        $signature = (string) ($payload['razorpay_signature'] ?? '');
        $secret = (string) config('payment.razorpay.key_secret');

        if ($orderId === '' || $paymentId === '' || $signature === '' || $secret === '') {
            return new VerificationResult(false, reason: 'incomplete_payload');
        }

        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $secret);

        return new VerificationResult(
            // hash_equals, not ===: a string comparison on a signature leaks
            // its prefix through timing.
            signatureValid: hash_equals($expected, $signature),
            gatewayTransactionId: $paymentId,
        );
    }

    public function status(Order $order): OrderStatusResult
    {
        try {
            $response = $this->http
                ->withBasicAuth(
                    (string) config('payment.razorpay.key_id'),
                    (string) config('payment.razorpay.key_secret'),
                )
                ->timeout(15)
                ->get('https://api.razorpay.com/v1/orders/'.$order->pg_ref_no.'/payments');
        } catch (Throwable) {
            // Unknown is not failed. Marking an order failed because the
            // network was unavailable would refund a candidate who paid.
            return new OrderStatusResult(status: $order->status);
        }

        if ($response->failed()) {
            return new OrderStatusResult(status: $order->status);
        }

        /** @var array<string, mixed> $body */
        $body = (array) $response->json();
        $payment = $body['items'][0] ?? null;

        if (! is_array($payment)) {
            return new OrderStatusResult(status: OrderStatus::Failed);
        }

        return new OrderStatusResult(
            status: ($payment['status'] ?? null) === 'captured' ? OrderStatus::Paid : OrderStatus::Failed,
            gatewayTransactionId: is_scalar($payment['id'] ?? null) ? (string) $payment['id'] : null,
            amountPaise: (int) ($payment['amount'] ?? 0),
            method: is_scalar($payment['method'] ?? null) ? (string) $payment['method'] : null,
            raw: $payment,
        );
    }

    /**
     * Razorpay's settlement report. Its own column order and its own status
     * vocabulary, mapped here so reconciliation never learns either.
     *
     * @return Collection<int, ReconciliationRow>
     */
    public function parseReconciliation(UploadedFile $file): Collection
    {
        $rows = new Collection;
        $handle = fopen($file->getRealPath(), 'rb');

        if ($handle === false) {
            return $rows;
        }

        $header = fgetcsv($handle);

        while (($columns = fgetcsv($handle)) !== false) {
            if ($columns === [null] || count($columns) < 4) {
                continue;
            }

            $rows->push(new ReconciliationRow(
                orderUid: trim((string) $columns[0]),
                gatewayTransactionId: trim((string) $columns[1]),
                status: trim((string) $columns[2]) === 'captured' ? OrderStatus::Paid : OrderStatus::Failed,
                amountPaise: (int) round(((float) $columns[3]) * 100),
            ));
        }

        fclose($handle);

        return $rows;
    }
}
