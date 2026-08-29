<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Advertisements, posts and corrigenda (domain-model.md §5).
     *
     * Note the cardinality: posts.advertisement_id, so an Advertisement has
     * many Posts. The previous domain model stated "Advertisement N:1 Post",
     * which is backwards -- advertisement 884 owns posts 2599 to 2602 in
     * production -- and the entire fee, date and eligibility model hangs off
     * Post, so the model built on it was invalid.
     */
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table): void {
            $table->id();
            $table->string('advertisement_no', 64)->unique();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();

            $table->foreignId('advertisement_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('organisational_unit_id')->nullable()->constrained()->nullOnDelete();

            /*
             * The organisational-unit snapshot, taken at publish. A department
             * renamed or re-parented in 2028 must not silently rewrite a 2026
             * advertisement, and no query should ever have to join across a
             * connection to render one.
             */
            $table->string('ou_code_snapshot', 64)->nullable();
            $table->string('ou_title_snapshot')->nullable();
            $table->string('ou_type_snapshot', 64)->nullable();
            $table->string('ou_path_snapshot', 255)->nullable()->index();

            // DR-010. Not a naming quirk: two genuinely different regimes with
            // different committees and different administration.
            $table->string('appointment_nature', 16)->default('general')->index();

            $table->date('dated')->nullable();
            $table->unsignedInteger('default_fee')->nullable();
            $table->date('default_opening_date')->nullable();
            $table->date('default_closing_date')->nullable();
            $table->date('default_payment_closing_date')->nullable();

            // Frozen at publish. Scoring-engine invariant I1: an advertisement
            // published under 2018 rules calculates under 2018 rules forever.
            $table->unsignedBigInteger('rule_set_version_id')->nullable();
            $table->unsignedBigInteger('relaxation_policy_version_id')->nullable();
            $table->string('payment_gateway', 32)->nullable();

            $table->string('status', 24)->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('approved_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('added_by_id')->nullable()->constrained('users')->nullOnDelete();

            // Maintained by observers, never computed per render: the
            // composite total/submitted/paid cell is on a list of thousands.
            $table->unsignedInteger('posts_count')->default(0);
            $table->unsignedInteger('applications_total')->default(0);
            $table->unsignedInteger('applications_submitted')->default(0);
            $table->unsignedInteger('applications_paid')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('posts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('advertisement_id')->constrained()->cascadeOnDelete();
            $table->foreignId('post_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('organisational_unit_id')->nullable()->constrained()->nullOnDelete();

            $table->string('ou_code_snapshot', 64)->nullable();
            $table->string('ou_title_snapshot')->nullable();
            $table->string('ou_type_snapshot', 64)->nullable();
            $table->string('ou_path_snapshot', 255)->nullable()->index();

            $table->unsignedInteger('serial_no')->nullable();
            $table->string('title');
            $table->string('subject')->nullable();
            $table->string('slug')->unique();

            $table->string('appointment_nature', 16)->default('general');
            $table->unsignedSmallInteger('tenure_months')->nullable();   // local only

            $table->unsignedInteger('vacancies')->default(1);
            $table->string('location')->nullable();
            $table->string('pay_level', 50)->nullable();
            $table->string('pay_range', 100)->nullable();
            $table->unsignedInteger('fee')->nullable();

            $table->date('opening_date')->nullable();
            $table->date('closing_date')->nullable()->index();
            $table->date('payment_closing_date')->nullable();

            /*
             * Restored. The previous redesign dropped all four groups, so
             * isAgeOverLimit(), isExperienceBelowLimit() and download-window
             * enforcement had no backing data at all.
             */
            $table->unsignedTinyInteger('age_limit')->nullable();
            $table->unsignedSmallInteger('min_experience_months')->nullable();
            $table->string('selection_method', 32)->nullable();

            $table->dateTime('admit_card_opening_date')->nullable();
            $table->dateTime('admit_card_closing_date')->nullable();
            $table->dateTime('interview_letter_opening_date')->nullable();
            $table->dateTime('interview_letter_closing_date')->nullable();

            $table->dateTime('test_date')->nullable();
            $table->time('test_reporting_time')->nullable();
            $table->time('gate_closing_time')->nullable();
            $table->unsignedSmallInteger('test_duration')->nullable();

            $table->dateTime('interview_date')->nullable();
            $table->string('interview_venue')->nullable();

            $table->boolean('withdrawn')->default(false);
            $table->string('status', 24)->default('draft')->index();
            $table->text('remark')->nullable();

            $table->unsignedInteger('applications_total')->default(0);
            $table->unsignedInteger('applications_submitted')->default(0);
            $table->unsignedInteger('applications_paid')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['advertisement_id', 'status']);
        });

        /*
         * DR-017: no post reservation applies at AMU except for persons with
         * disability. The breakup therefore records what an advertisement
         * declares, and is not a roster engine.
         */
        Schema::create('post_vacancy_breakup', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->string('category', 16)->nullable();
            $table->string('horizontal_category', 16)->nullable();
            $table->unsignedInteger('count')->default(0);
            $table->timestamps();

            $table->index('post_id');
        });

        /*
         * Corrigenda are objects, not edits. A date extension or an
         * eligibility correction is published, dated and auditable. The legacy
         * system appended a unix timestamp to the slug as a de-dup hack.
         */
        Schema::create('corrigenda', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('advertisement_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('corrigendum_no');
            $table->date('issued_on');
            $table->text('description');
            $table->json('changes')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->foreignId('issued_by_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['advertisement_id', 'corrigendum_no']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('corrigenda');
        Schema::dropIfExists('post_vacancy_breakup');
        Schema::dropIfExists('posts');
        Schema::dropIfExists('advertisements');
    }
};
