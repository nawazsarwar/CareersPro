<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Local, autonomous organisational units (DR-009).
     *
     * These are the single runtime source of truth. The portal reads only from
     * them and works with the Data Lake switched off, unreachable, or never
     * connected -- a model bound directly to a `datalake` connection fails hard
     * when that connection is absent, which is the failure DR-009 exists to
     * prevent.
     *
     * They arrive in Wave 1 rather than Wave 2 because M25's second
     * authorisation scope resolves through them, and an authorisation scope
     * cannot wait for the wave after the one that enforces it. M24 adds the
     * provider and the import on top of this schema.
     *
     * Seven corrections against the source schema, from decision-register §6.
     */
    public function up(): void
    {
        Schema::create('organisational_unit_types', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('organisational_unit_types')->nullOnDelete();
            $table->string('title');
            $table->string('code', 32)->unique();

            // (3) An enum, not a varchar. The source held two distinct values
            // in a varchar(255), which invites drift.
            $table->string('category', 16);

            // (6) Only some of the 29 source types can carry a vacancy, and
            // nothing recorded which.
            $table->boolean('is_recruitment_eligible')->default(false);

            // Import provenance for idempotent re-sync, NOT a runtime link.
            // Nothing reads it at request time.
            $table->unsignedBigInteger('datalake_id')->nullable()->unique();

            $table->timestamps();
        });

        Schema::create('organisational_units', function (Blueprint $table): void {
            $table->id();

            // (4) Real foreign keys. The source had only indexes *named*
            // `parent_fk_*` -- the intent was there and the constraint was lost.
            $table->foreignId('parent_id')->nullable()->constrained('organisational_units')->nullOnDelete();
            $table->foreignId('type_id')->constrained('organisational_unit_types')->restrictOnDelete();

            $table->string('title');
            $table->string('title_hindi')->nullable();   // (7) kept; AMU is multilingual
            $table->string('title_urdu')->nullable();

            // (1) NOT NULL. It is the snapshot identifier that survives a
            // rename, so it cannot be optional. Ten of the 301 source rows
            // have none and are back-filled at import.
            $table->string('code', 64)->unique();

            // (5) The materialised path, e.g. /1/11/27/. Dean-scoped
            // authorisation runs on every admin request and must be one
            // indexed LIKE, not a recursive walk.
            $table->string('path', 255)->index();

            $table->string('status', 16)->default('published')->index();

            $table->unsignedBigInteger('datalake_id')->nullable()->unique();

            $table->timestamps();
        });

        // (2) `category` is deliberately absent from units: it was NULL in all
        // 301 source rows, and the category genuinely belongs to the type.
    }

    public function down(): void
    {
        Schema::dropIfExists('organisational_units');
        Schema::dropIfExists('organisational_unit_types');
    }
};
