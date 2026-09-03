<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The append-only, hash-chained audit log (M26 §2).
     *
     * What this replaces was a stock activity-log table with a mutable
     * updated_at, no hash, no previous_hash and no sequence, while three design
     * documents asserted hash-chained immutability. Immutability here is
     * enforced by the database, not by convention: triggers reject UPDATE and
     * DELETE, so a direct connection cannot quietly rewrite a decision.
     */
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table): void {
            $table->bigIncrements('id');

            // Global, gapless and monotonic. Gapless is asserted by M26-R05,
            // so it is allocated under a row lock rather than by auto-increment,
            // which skips on rollback.
            $table->unsignedBigInteger('sequence')->unique();

            $table->char('previous_hash', 64);
            $table->char('hash', 64);

            $table->string('event', 64);

            // A morph alias, never a class name: a namespace change must not
            // orphan six years of records.
            $table->string('subject_type', 64)->nullable();
            $table->unsignedBigInteger('subject_id')->nullable();

            $table->unsignedBigInteger('actor_id')->nullable();      // NULL = system
            $table->unsignedBigInteger('impersonator_id')->nullable(); // M26-R10
            $table->string('actor_ip', 45)->nullable();
            $table->string('actor_role', 64)->nullable();

            $table->json('properties');

            $table->timestamp('occurred_at', 6);

            // Deliberately no updated_at and no soft delete.

            $table->index(['subject_type', 'subject_id', 'sequence']);
            $table->index(['actor_id', 'occurred_at']);
            $table->index(['event', 'occurred_at']);
        });

        Schema::create('audit_checkpoints', function (Blueprint $table): void {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sequence')->unique();
            $table->char('cumulative_hash', 64);
            $table->timestamp('created_at', 6);
        });

        // The counter the sequence is allocated from. A single row, taken
        // FOR UPDATE, is what makes concurrent writers agree on the next value.
        Schema::create('audit_sequence', function (Blueprint $table): void {
            $table->unsignedTinyInteger('id')->primary();
            $table->unsignedBigInteger('next_value');
        });

        DB::table('audit_sequence')->insert(['id' => 1, 'next_value' => 1]);

        $this->guardAgainstMutation();
    }

    /**
     * M26-R02 and M26-R03 — the database refuses UPDATE and DELETE.
     *
     * Written per driver because the syntax differs and because SQLite, which
     * the test suite runs on, must enforce the same rule as MySQL. A guard that
     * only exists in production is a guard that is never tested.
     */
    private function guardAgainstMutation(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::statement(<<<'SQL'
                CREATE TRIGGER audit_logs_no_update
                BEFORE UPDATE ON audit_logs
                BEGIN
                    SELECT RAISE(ABORT, 'audit_logs is append-only: UPDATE is refused.');
                END
            SQL);

            DB::statement(<<<'SQL'
                CREATE TRIGGER audit_logs_no_delete
                BEFORE DELETE ON audit_logs
                BEGIN
                    SELECT RAISE(ABORT, 'audit_logs is append-only: DELETE is refused.');
                END
            SQL);

            return;
        }

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::unprepared(<<<'SQL'
                CREATE TRIGGER audit_logs_no_update
                BEFORE UPDATE ON audit_logs
                FOR EACH ROW
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'audit_logs is append-only: UPDATE is refused.';
            SQL);

            DB::unprepared(<<<'SQL'
                CREATE TRIGGER audit_logs_no_delete
                BEFORE DELETE ON audit_logs
                FOR EACH ROW
                    SIGNAL SQLSTATE '45000'
                    SET MESSAGE_TEXT = 'audit_logs is append-only: DELETE is refused.';
            SQL);
        }
    }

    public function down(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if (in_array($driver, ['sqlite', 'mysql', 'mariadb'], true)) {
            DB::statement('DROP TRIGGER IF EXISTS audit_logs_no_update');
            DB::statement('DROP TRIGGER IF EXISTS audit_logs_no_delete');
        }

        Schema::dropIfExists('audit_sequence');
        Schema::dropIfExists('audit_checkpoints');
        Schema::dropIfExists('audit_logs');
    }
};
