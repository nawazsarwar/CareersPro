<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Legacy provenance, and the quarantine.
     *
     * `legacy_id` makes migration idempotent: every migrated row carries its
     * source id, so a re-run updates rather than duplicating. A migration that
     * cannot be re-run safely is a migration nobody dares re-run, which means
     * every defect found halfway through is a restore-from-backup.
     */
    public function up(): void
    {
        foreach (['users', 'applications', 'orders'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->unsignedBigInteger('legacy_id')->nullable()->unique();
            });
        }

        /*
         * Rows that could not be mapped are quarantined and reported, never
         * guessed. The organisational unit is the sharp case: legacy
         * `posts` has no unit at all -- the department lives inside free text
         * such as "Assistant Professor, Dept of Conservative Dentistry &
         * Endodontics" -- so roughly 2,874 posts need a mapping that cannot be
         * derived automatically with confidence.
         */
        Schema::create('migration_quarantine', function (Blueprint $table): void {
            $table->id();
            $table->string('source_table', 64)->index();
            $table->unsignedBigInteger('source_id');
            $table->string('reason', 64);
            $table->json('payload');
            $table->text('note')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['source_table', 'source_id']);
            $table->index(['source_table', 'resolved_at']);
        });

        Schema::create('migration_runs', function (Blueprint $table): void {
            $table->id();
            $table->string('source_table', 64);
            $table->boolean('dry_run')->default(false);
            $table->unsignedInteger('rows_read')->default(0);
            $table->unsignedInteger('rows_written')->default(0);
            $table->unsignedInteger('rows_quarantined')->default(0);
            $table->string('status', 24)->default('running');
            $table->timestamp('started_at');
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migration_runs');
        Schema::dropIfExists('migration_quarantine');

        foreach (['users', 'applications', 'orders'] as $table) {
            Schema::table($table, function (Blueprint $blueprint): void {
                $blueprint->dropColumn('legacy_id');
            });
        }
    }
};
