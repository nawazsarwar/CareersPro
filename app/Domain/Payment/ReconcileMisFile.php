<?php

declare(strict_types=1);

namespace App\Domain\Payment;

use App\Domain\Audit\AuditEvent;
use App\Domain\Audit\RecordAuditEvent;
use App\Enums\AuditEventName;
use App\Models\Order;
use App\Models\Reconciliation;
use App\Models\ReconciliationRowRecord;
use App\Models\User;
use Illuminate\Http\UploadedFile;

/**
 * The gateway's record wins.
 *
 * Where the settlement file and local state disagree, the gateway is
 * authoritative and the disagreement is kept as a row. A reconciliation that
 * silently corrected local state would destroy the evidence that it was ever
 * wrong -- and the ~29 per cent failure tail in production is exactly the
 * population where that evidence matters.
 *
 * This class has never heard of any provider. Each adapter maps its own file
 * to ReconciliationRow, and an architecture test asserts that no vendor name
 * appears anywhere outside the Gateways namespace -- including in a comment,
 * because a boundary that holds only in code is one somebody talks their way
 * across in review.
 */
final class ReconcileMisFile
{
    public function __construct(
        private readonly GatewayRegistry $gateways,
        private readonly RecordAuditEvent $audit,
    ) {}

    public function handle(UploadedFile $file, string $gatewayName, ?User $actor = null): Reconciliation
    {
        $rows = $this->gateways->for($gatewayName)->parseReconciliation($file);

        $reconciliation = Reconciliation::query()->create([
            'gateway' => $gatewayName,
            'file_name' => $file->getClientOriginalName(),
            'uploaded_by_id' => $actor?->getKey(),
            'rows_total' => $rows->count(),
            'status' => 'running',
        ]);

        $matched = 0;
        $discrepant = 0;

        foreach ($rows as $row) {
            $order = Order::query()->where('order_uid', $row->orderUid)->first();

            if ($order === null) {
                // A payment against an order we have no record of. Never
                // silently dropped: it is money that moved.
                ReconciliationRowRecord::query()->create([
                    'reconciliation_id' => $reconciliation->getKey(),
                    'gateway_txn_id' => $row->gatewayTransactionId,
                    'gateway_status' => $row->status->value,
                    'gateway_amount_paise' => $row->amountPaise,
                    'outcome' => 'unknown_order',
                    'note' => 'No local order matches this reference.',
                ]);

                $discrepant++;

                continue;
            }

            $agrees = $order->status === $row->status
                && $order->amount_paise === $row->amountPaise;

            ReconciliationRowRecord::query()->create([
                'reconciliation_id' => $reconciliation->getKey(),
                'order_id' => $order->getKey(),
                'gateway_txn_id' => $row->gatewayTransactionId,
                'gateway_status' => $row->status->value,
                'local_status' => $order->status->value,
                'gateway_amount_paise' => $row->amountPaise,
                'outcome' => $agrees ? 'matched' : 'discrepant',
                'note' => $agrees ? null : $this->describe($order, $row),
            ]);

            if ($agrees) {
                $matched++;

                continue;
            }

            $discrepant++;

            // The gateway wins. A candidate the gateway says paid is paid,
            // whatever a dropped callback left behind locally.
            $order->forceFill([
                'status' => $row->status,
                'settled_at' => $row->status->isSettled() ? now() : $order->settled_at,
            ])->save();

            if ($row->status->grantsAccess()) {
                $order->application()->update(['paid' => true, 'order_id' => $order->getKey()]);
            }
        }

        $reconciliation->forceFill([
            'rows_matched' => $matched,
            'rows_discrepant' => $discrepant,
            'status' => 'complete',
        ])->save();

        $this->audit->handle(new AuditEvent(
            event: AuditEventName::ReconciliationRun,
            properties: [
                'gateway' => $gatewayName,
                'rows' => $rows->count(),
                'matched' => $matched,
                'discrepant' => $discrepant,
            ],
            subject: $reconciliation,
            actorId: $actor?->getKey() === null ? null : (int) $actor->getKey(),
        ));

        return $reconciliation->refresh();
    }

    private function describe(Order $order, ReconciliationRow $row): string
    {
        if ($order->amount_paise !== $row->amountPaise) {
            return sprintf(
                'Amount differs: local %d paise, gateway %d paise.',
                $order->amount_paise,
                $row->amountPaise,
            );
        }

        return sprintf(
            'Status differs: local %s, gateway %s.',
            $order->status->value,
            $row->status->value,
        );
    }
}
