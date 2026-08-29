<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The versioned ruleset and the scoring record (M20 §2).
     *
     * A rule set is data, not code. The previous work's docs/research
     * ugc-rules.yaml was invented rather than transcribed: it asserted that a
     * Principal Investigator scores 100 per cent where the Gazette says 50 per
     * cent each, which would have made every Associate Professor and Professor
     * determination wrong -- and wrong in a direction a rejected candidate can
     * challenge in court.
     */
    public function up(): void
    {
        Schema::create('rule_sets', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('title');
            $table->json('applies_to');            // which cadres this governs
            $table->string('design_doc')->nullable();
            $table->timestamps();
        });

        Schema::create('rule_set_versions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('rule_set_id')->constrained()->cascadeOnDelete();
            $table->string('version', 32);
            $table->string('status', 16)->default('draft');

            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();

            $table->json('payload');
            $table->char('content_hash', 64);

            /*
             * Separation of duties (M25-R06, M25-R07). rules_admin authors,
             * rules_verifier activates, and they must be different people.
             * This is the control that would have stopped the fabricated
             * ruleset reaching production.
             */
            $table->boolean('second_reader_verified')->default(false);
            $table->foreignId('authored_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('verified_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();

            $table->unique(['rule_set_id', 'version']);
            $table->index(['rule_set_id', 'status']);
        });

        Schema::create('score_runs', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('application_id')->constrained()->cascadeOnDelete();

            // I2: the input is a snapshot, and snapshots are append-only. A
            // score computed against a live dossier could not be reproduced
            // once the dossier changed.
            $table->foreignId('snapshot_id')->constrained('application_snapshots')->cascadeOnDelete();

            // I1: the version comes from the application, never from whatever
            // happens to be active today.
            $table->foreignId('rule_set_version_id')->constrained()->restrictOnDelete();

            $table->string('strategy', 32);
            $table->decimal('total', 8, 2)->nullable();

            // Blocked is a real outcome, not an error. I5: the engine refuses
            // rather than guessing where a rule is unratified.
            $table->string('status', 16);
            $table->string('blocked_by_rule', 96)->nullable();

            // I3: determinism. input_hash is H(snapshot ‖ ruleset); re-running
            // the same input must produce the same output_hash.
            $table->char('input_hash', 64)->index();
            $table->char('output_hash', 64)->nullable();

            $table->boolean('is_sandbox')->default(false);
            $table->timestamp('computed_at');
            $table->foreignId('computed_by_id')->nullable()->constrained('users')->nullOnDelete();

            $table->index(['application_id', 'computed_at']);
        });

        Schema::create('score_lines', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('score_run_id')->constrained()->cascadeOnDelete();

            // I4: both NOT NULL. A total without per-line citations is not a
            // valid output -- it is a number the University cannot defend to
            // the candidate it was used against.
            $table->string('rule_id', 96);
            $table->string('citation', 191);

            $table->unsignedBigInteger('claim_id')->nullable();
            $table->decimal('raw_value', 10, 3)->nullable();
            $table->decimal('apportionment_factor', 5, 3)->default(1);
            $table->decimal('points', 8, 2)->default(0);
            $table->text('explanation');

            $table->unique(['score_run_id', 'rule_id', 'claim_id'], 'score_line_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('score_lines');
        Schema::dropIfExists('score_runs');
        Schema::dropIfExists('rule_set_versions');
        Schema::dropIfExists('rule_sets');
    }
};
