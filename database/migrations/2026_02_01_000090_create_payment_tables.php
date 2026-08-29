<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fee and payment (M08 §2).
     *
     * The previous build had NO payment schema at all -- orders, transactions,
     * receivables and services were marked "Pending/Staged" -- while
     * production carries 45,280 orders and 2.29 crore of collected fees, with
     * roughly 29 per cent of transactions failing and a manual refund path.
     *
     * No card data is stored here. Not masked, not hashed, not at all.
     */
    public function up(): void
    {
        Schema::create('fee_rules', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('advertisement_id')->nullable()->constrained()->cascadeOnDelete();

            // NULL category = the default fee. A row per concession rather
            // than a conditional in code, so the Vice-Chancellor's schedule
            // (CRR Rule 11 III(c)) is editable without a deployment.
            $table->string('category', 16)->nullable();
            $table->string('horizontal_category', 16)->nullable();

            $table->unsignedInteger('amount_paise');
            $table->boolean('is_exempt')->default(false);
            $table->timestamps();

            $table->index(['advertisement_id', 'category']);
            $table->index(['post_id', 'category']);
        });

        Schema::create('orders', function (Blueprint $table): void {
            $table->id();
            $table->string('order_uid', 40)->unique();
            $table->foreignId('application_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();

            /*
             * The load-bearing column. Derived from sha256(user|post|attempt),
             * so calling CreateOrder twice for the same attempt returns the
             * same order rather than creating a second one.
             *
             * This is the double-deduction fix: CU-Chayan's users report being
             * charged twice at deadline hours, and the legacy portal's 45,280
             * orders against 0 transactions say the same thing.
             */
            $table->string('idempotency_key', 64)->unique();

            $table->unsignedInteger('amount_paise');
            $table->string('currency', 3)->default('INR');

            // Copied from the advertisement, which froze it at publish. An
            // order must settle through the gateway that was in force when the
            // candidate read the terms.
            $table->string('gateway', 32);

            $table->string('pg_ref_no')->nullable()->index();
            $table->string('status', 24)->default('created')->index();

            $table->timestamp('initiated_at')->nullable();
            $table->timestamp('settled_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('application_id');
        });

        Schema::create('transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // Unique, so the same gateway transaction cannot be recorded twice
            // by a callback and a reconciliation both claiming it.
            $table->string('gateway_txn_id')->unique();

            $table->string('status', 24);
            $table->unsignedInteger('amount_paise');
            $table->string('method', 32)->nullable();       // upi, netbanking, card — never the instrument
            $table->json('gateway_payload')->nullable();
            $table->timestamp('occurred_at');
            $table->timestamps();

            $table->index(['order_id', 'occurred_at']);
        });

        Schema::create('reconciliations', function (Blueprint $table): void {
            $table->id();
            $table->string('gateway', 32);
            $table->string('file_name');
            $table->foreignId('uploaded_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedInteger('rows_total')->default(0);
            $table->unsignedInteger('rows_matched')->default(0);
            $table->unsignedInteger('rows_discrepant')->default(0);
            $table->string('status', 24)->default('pending');
            $table->timestamps();
        });

        /*
         * Where the gateway's record and ours disagree, the gateway wins and
         * the disagreement is kept. A reconciliation that silently corrected
         * local state would destroy the evidence that it was ever wrong.
         */
        Schema::create('reconciliation_rows', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('reconciliation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('gateway_txn_id')->nullable()->index();
            $table->string('gateway_status', 24)->nullable();
            $table->string('local_status', 24)->nullable();
            $table->unsignedInteger('gateway_amount_paise')->nullable();
            $table->string('outcome', 24);      // matched, discrepant, unknown_order
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['reconciliation_id', 'outcome']);
        });

        Schema::create('receipts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('receipt_no', 40)->unique();
            $table->timestamp('issued_at');
            $table->timestamps();
        });

        Schema::create('refunds', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('amount_paise');
            $table->string('reason', 64);
            $table->string('gateway_refund_id')->nullable()->unique();
            $table->string('status', 24)->default('requested');
            $table->foreignId('requested_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('refunds');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('reconciliation_rows');
        Schema::dropIfExists('reconciliations');
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('fee_rules');
    }
};
