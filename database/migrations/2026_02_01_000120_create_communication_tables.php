<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Mass communication, grievances and hard-copy custody.
     *
     * The mass-emailing engine is required by the project's own brief and
     * appears in no previous specification at all, despite being visible in
     * the production screenshots.
     */
    public function up(): void
    {
        Schema::create('message_templates', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 64)->unique();
            $table->string('name');
            $table->string('channel', 16);          // email, sms
            $table->string('subject')->nullable();
            $table->text('body');

            // Declared, so a template referencing a field the segment cannot
            // supply is caught before it is sent to 78,232 people.
            $table->json('placeholders')->nullable();

            $table->timestamps();
        });

        Schema::create('message_campaigns', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('message_template_id')->constrained()->restrictOnDelete();
            $table->foreignId('post_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->json('segment');                // the filter that chose the recipients
            $table->string('status', 24)->default('draft');
            $table->unsignedInteger('recipients_total')->default(0);
            $table->unsignedInteger('recipients_sent')->default(0);
            $table->unsignedInteger('recipients_failed')->default(0);
            $table->foreignId('created_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        /*
         * One row per recipient. A campaign that reports "sent" without saying
         * to whom cannot answer the only question that matters afterwards:
         * did this candidate get told?
         */
        Schema::create('message_logs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('message_campaign_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('application_id')->nullable()->constrained()->nullOnDelete();
            $table->string('channel', 16);

            // A blind index over the address, not the address: a message log
            // is not a second copy of the contact columns
            // (data-protection.md §2).
            $table->string('destination_hash', 64)->index();

            $table->string('status', 24);
            $table->text('failure_reason')->nullable();
            $table->string('provider_reference')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['message_campaign_id', 'status']);
        });

        Schema::create('grievances', function (Blueprint $table): void {
            $table->id();
            $table->string('reference', 32)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('application_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category', 48);
            $table->text('description');
            $table->string('status', 24)->default('open')->index();

            // The SLA clock. A grievance desk without one is a suggestion box.
            $table->timestamp('due_at')->nullable()->index();
            $table->timestamp('escalated_at')->nullable();

            $table->foreignId('assigned_to_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'due_at']);
        });

        Schema::create('grievance_messages', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('grievance_id')->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body');
            $table->boolean('is_internal')->default(false);
            $table->timestamps();

            $table->index('grievance_id');
        });

        /*
         * DR-011. Electronic records are kept indefinitely; the five-year
         * weeding is a PHYSICAL custody process. Nothing here deletes an
         * application -- it records what happened to a box of paper.
         */
        Schema::create('hardcopy_receipts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->unique()->constrained()->restrictOnDelete();
            $table->timestamp('received_at');
            $table->string('storage_location')->nullable();
            $table->foreignId('received_by_id')->nullable()->constrained('users')->nullOnDelete();

            // Admitted late on proof of timely posting (CRR Rule 11 III(d)),
            // which is the Vice-Chancellor's decision and is recorded as one.
            $table->boolean('admitted_late')->default(false);
            $table->string('postal_proof_reference')->nullable();

            // Five years after process close, for UNSUCCESSFUL candidates only.
            $table->date('destruction_due_on')->nullable()->index();
            $table->foreignId('destruction_batch_id')->nullable();
            $table->timestamps();
        });

        Schema::create('destruction_batches', function (Blueprint $table): void {
            $table->id();
            $table->string('reference', 32)->unique();
            $table->date('destroyed_on');
            $table->foreignId('authorised_by_id')->constrained('users')->restrictOnDelete();
            $table->unsignedInteger('dossier_count')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destruction_batches');
        Schema::dropIfExists('hardcopy_receipts');
        Schema::dropIfExists('grievance_messages');
        Schema::dropIfExists('grievances');
        Schema::dropIfExists('message_logs');
        Schema::dropIfExists('message_campaigns');
        Schema::dropIfExists('message_templates');
    }
};
