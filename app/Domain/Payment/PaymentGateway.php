<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Models\Order;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

/**
 * The only thing the domain knows about payment (DR-018).
 *
 * No vendor name appears outside App\Domain\Payment\Gateways, and an
 * architecture test asserts it.
 */
interface PaymentGateway
{
    public function name(): string;

    /**
     * @return array<string, mixed> what the browser posts or redirects to
     */
    public function initiate(Order $order): array;

    /**
     * Verifies a callback's signature. Necessary, and never sufficient: the
     * caller still asks status() before marking anything paid.
     *
     * @param  array<string, mixed>  $payload
     */
    public function verify(array $payload): VerificationResult;

    /**
     * Server-to-server, and authoritative. This is what a callback is checked
     * against.
     */
    public function status(Order $order): OrderStatusResult;

    /**
     * Each adapter maps its own MIS format to the common row shape.
     * Reconciliation itself has never heard of either vendor.
     *
     * @return Collection<int, ReconciliationRow>
     */
    public function parseReconciliation(UploadedFile $file): Collection;
}
