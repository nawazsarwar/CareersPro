<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('applications', function (Blueprint $table): void {
            $table->id();
            $table->string('application_no', 32)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('post_id')->constrained()->restrictOnDelete();
            $table->foreignId('advertisement_id')->constrained()->restrictOnDelete();

            $table->timestamp('submitted_at')->nullable();
            $table->boolean('submitted')->default(false);

            // Copied from the advertisement at submit and read-only after.
            // Invariant I1 again, one level down: the application is scored
            // under the rules that were in force when it was made.
            $table->unsignedBigInteger('rule_set_version_id')->nullable();
            $table->unsignedBigInteger('relaxation_policy_version_id')->nullable();

            $table->string('applied_under_category', 16)->nullable();
            $table->string('applied_under_horizontal_category', 16)->nullable();
            $table->boolean('is_internal_candidate')->default(false);

            $table->boolean('paid')->default(false);
            $table->unsignedBigInteger('order_id')->nullable();

            // A PHP enum, not a nullable integer that the code writes the
            // string 'Submitted' into -- which is what the previous wizard did.
            $table->string('lifecycle_state', 32)->default('draft')->index();

            // DR-011: archived, never deleted.
            $table->timestamp('archived_at')->nullable();

            $table->string('roll_no', 32)->nullable()->index();
            $table->foreignId('centre_id')->nullable()->constrained('exam_centres')->nullOnDelete();
            $table->string('room_no', 32)->nullable();
            $table->string('seat_no', 32)->nullable();
            $table->timestamp('admit_card_downloaded_at')->nullable();
            $table->timestamp('interview_letter_downloaded_at')->nullable();

            $table->timestamp('withdrawn_at')->nullable();
            $table->text('withdrawal_reason')->nullable();

            $table->timestamps();

            // One application per candidate per post, enforced by the database
            // rather than by a check somebody can forget to call.
            $table->unique(['user_id', 'post_id']);
            $table->index(['post_id', 'lifecycle_state']);
        });

        /*
         * Append-only. "View this application exactly as it was scored on date
         * X" is a statutory requirement (CRR Rule 22.4, RTI), and it cannot be
         * answered by a table that can be updated.
         */
        Schema::create('application_snapshots', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->timestamp('taken_at', 6);
            $table->string('reason', 32);
            $table->json('payload');
            $table->char('content_hash', 64)->index();

            $table->index(['application_id', 'taken_at']);
        });

        /*
         * The three gates as a table, not three column groups. A post type
         * with one active gate simply has one row, and the UI cannot offer a
         * written-test decision for a post that has no written test.
         *
         * `decision` is nullable with three meanings: eligible, rejected, and
         * NULL for pending. The legacy UI rendered a merged
         * "Pending / Not Eligible" label over all three, on a legally
         * consequential decision.
         */
        Schema::create('eligibility_decisions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('gate', 24);
            $table->string('decision', 16)->nullable();
            $table->text('remark')->nullable();
            $table->foreignId('decided_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('decided_at')->nullable();
            $table->timestamps();

            $table->unique(['application_id', 'gate']);
        });

        Schema::create('deficiencies', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('raised_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('raised_at');
            $table->string('field_reference')->nullable();
            $table->text('description');

            // The differentiator CU-Chayan lacks: a time-bound window in which
            // the candidate can actually fix what was found, rather than a
            // rejection they learn about afterwards.
            $table->timestamp('rectification_window_closes_at')->nullable();

            $table->timestamp('rectified_at')->nullable();
            $table->foreignId('rectified_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('resolution')->nullable();
            $table->timestamps();

            $table->index(['application_id', 'rectified_at']);
        });

        Schema::create('application_status_history', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();
            $table->string('from_state', 32)->nullable();
            $table->string('to_state', 32);
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('at');
            $table->text('reason')->nullable();

            $table->index(['application_id', 'at']);
        });

        // A sequence per post, so application numbers are gapless within a
        // post and allocated under a row lock rather than by max()+1, which
        // races.
        Schema::create('application_number_sequences', function (Blueprint $table): void {
            $table->foreignId('post_id')->primary()->constrained()->cascadeOnDelete();
            $table->unsignedInteger('next_value')->default(1);
        });

        $this->guardSnapshots();
    }

    /**
     * Snapshots are append-only in the database, for the same reason the audit
     * log is: a snapshot that can be edited proves nothing about what was
     * scored.
     */
    private function guardSnapshots(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement(<<<'SQL'
                CREATE TRIGGER application_snapshots_no_update
                BEFORE UPDATE ON application_snapshots
                BEGIN
                    SELECT RAISE(ABORT, 'application_snapshots is append-only: UPDATE is refused.');
                END
            SQL);

            return;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::unprepared(<<<'SQL'
                CREATE TRIGGER application_snapshots_no_update
                BEFORE UPDATE ON application_snapshots
                FOR EACH ROW
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'application_snapshots is append-only: UPDATE is refused.';
            SQL);
        }
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS application_snapshots_no_update');

        Schema::dropIfExists('application_number_sequences');
        Schema::dropIfExists('application_status_history');
        Schema::dropIfExists('deficiencies');
        Schema::dropIfExists('eligibility_decisions');
        Schema::dropIfExists('application_snapshots');
        Schema::dropIfExists('applications');
    }
};
