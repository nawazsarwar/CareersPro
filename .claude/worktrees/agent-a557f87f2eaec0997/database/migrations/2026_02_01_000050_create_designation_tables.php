<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The missing spine (DR-012, M35 §2).
     *
     * Today a post carries a free-text title, so nothing connects a vacancy to
     * the regulation that governs it -- legacy careers_db.posts holds
     * "Assistant Professor, Dept of Conservative Dentistry & Endodontics" as a
     * string. A Post becomes an instance of a Designation, in an
     * Organisational Unit, under an Advertisement, which is what lets the
     * rules engine bind to something stable.
     */
    public function up(): void
    {
        Schema::create('designations', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('name_short', 64)->nullable();

            $table->string('cadre', 24)->index();

            // Non-teaching only. NULL for a teaching cadre is meaningful, not
            // missing: teaching posts have no Group.
            $table->string('group', 1)->nullable()->index();

            $table->string('pay_level', 50);
            $table->string('pay_range', 100)->nullable();
            $table->unsignedTinyInteger('retirement_age')->nullable();

            $table->unsignedTinyInteger('min_age')->nullable();
            $table->unsignedTinyInteger('max_age')->nullable();

            // CRR Rule 14. The column exists to make the rule explicit and
            // greppable, not to make it configurable -- there is exactly one
            // permitted value.
            $table->string('age_reference', 32)->default('application_closing_date');

            $table->json('essential_qualification')->nullable();
            $table->json('desirable_qualification')->nullable();
            $table->json('experience_rules')->nullable();
            $table->json('method_of_recruitment')->nullable();

            // Recorded from Schedule-1's inline column; M19 reads it.
            $table->json('committee_composition')->nullable();

            $table->string('selection_method', 32);

            $table->string('status', 16)->default('active');
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['cadre', 'status']);
        });

        /*
         * The sanctioned-strength register required by CRR Rules 8 and 9.1.
         * It exists in no system today: MODULES.md #16 promised "post creation
         * linked to sanctioned strength" with no backing data in either
         * database.
         */
        Schema::create('organisational_unit_designation', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('organisational_unit_id')->constrained()->cascadeOnDelete();
            $table->foreignId('designation_id')->constrained()->cascadeOnDelete();

            $table->unsignedInteger('sanctioned_count');
            $table->unsignedInteger('filled_count')->default(0);

            // Required whenever the count changes: a sanctioned strength
            // without its order reference is a number nobody can defend.
            $table->string('sanction_order_ref')->nullable();
            $table->date('sanctioned_on')->nullable();

            $table->timestamps();

            $table->unique(['organisational_unit_id', 'designation_id'], 'ou_designation_unique');
            $table->index('organisational_unit_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisational_unit_designation');
        Schema::dropIfExists('designations');
    }
};
