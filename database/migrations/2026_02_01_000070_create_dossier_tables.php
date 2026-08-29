<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The reusable dossier -- apply once, reuse everywhere (domain-model.md §6).
     *
     * The profile columns that authentication did not need are added here,
     * now that M24 has created the lookup tables they reference. Wave 1
     * deliberately did not create them as nullable placeholders pointing at
     * tables that did not exist.
     */
    public function up(): void
    {
        Schema::table('profiles', function (Blueprint $table): void {
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('fathers_name')->nullable();

            // Separate, because the legacy forms merged "Father's/Husband's
            // Name" into one field and a mother's name could not be recorded
            // at all.
            $table->string('mothers_name')->nullable();
            $table->string('spouse_name')->nullable();

            $table->date('dob')->nullable();

            // A discrete field. The legacy forms left gender inferable only
            // from the Mr./Mrs./Miss prefix, which cannot record everybody.
            $table->string('gender', 16)->nullable();

            $table->foreignId('nationality_id')->nullable()->constrained('countries')->nullOnDelete();
            $table->foreignId('marital_status_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('religion_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('caste_id')->nullable()->constrained()->nullOnDelete();
            $table->string('sub_caste')->nullable();

            // OBC-NCL and EWS certificates expire; SC and ST do not. Recorded
            // because a certificate valid at application and expired at
            // scrutiny is a live dispute.
            $table->string('category_certificate_no')->nullable();
            $table->date('category_certificate_valid_until')->nullable();

            $table->string('place_of_birth')->nullable();
            $table->foreignId('state_of_birth_id')->nullable()->constrained('provinces')->nullOnDelete();
            $table->foreignId('domicile_state_id')->nullable()->constrained('provinces')->nullOnDelete();

            $table->text('alternate_mobile')->nullable();

            // S2 under data-protection.md §2: encrypted, with a blind index so
            // duplicates can be detected without decrypting a single row.
            $table->text('aadhaar_no')->nullable();
            $table->string('aadhaar_blind_index', 64)->nullable()->index();

            $table->text('identity_marks')->nullable();

            $table->boolean('is_pwd')->default(false);
            $table->foreignId('disability_type_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('disability_percent')->nullable();
            $table->string('disability_certificate_authority')->nullable();

            $table->boolean('is_ex_serviceman')->default(false);
            $table->date('esm_discharge_date')->nullable();

            // Declarations. CRR Rule 33.3 is recorded and never blocking
            // (OQ-012 is with the legal cell).
            $table->boolean('has_conviction')->default(false);
            $table->text('conviction_details')->nullable();
            $table->boolean('is_debarred')->default(false);
            $table->text('debarment_details')->nullable();
            $table->boolean('rule_33_3_declared')->default(false);

            $table->boolean('locked')->default(false);
        });

        Schema::create('addresses', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 20);
            $table->string('line1');
            $table->string('line2')->nullable();
            $table->string('city')->nullable();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('province_id')->nullable()->constrained()->nullOnDelete();
            $table->string('postal_code', 10)->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'type']);
        });

        Schema::create('academic_qualifications', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('qualification_level_id')->constrained()->restrictOnDelete();
            $table->foreignId('board_id')->nullable()->constrained()->nullOnDelete();
            $table->string('course')->nullable();
            $table->string('subjects')->nullable();
            $table->unsignedSmallInteger('year_of_passing')->nullable();
            $table->string('division', 32)->nullable();
            $table->decimal('percentage', 5, 2)->nullable();
            $table->decimal('cgpa', 5, 2)->nullable();
            $table->decimal('cgpa_scale', 5, 2)->nullable();

            // DR-016: the conversion is declared by the candidate with
            // documentary proof, because cl. 3.6 defers to the awarding
            // university's own formula and there is no single algorithm.
            $table->json('conversion_declaration')->nullable();

            $table->unsignedTinyInteger('ncrf_level')->nullable();

            /*
             * The NET-exemption gateway, and the single most-used eligibility
             * pathway in the system. The 2018 clause names the 2009 and 2016
             * Regulations only; the 2022 Regulations superseded 2016 and
             * abolished M.Phil, and whether a 2022-compliant PhD triggers the
             * exemption is unresolved (DOC-002). The column records what the
             * candidate holds so the engine can refuse rather than guess.
             */
            $table->string('phd_regulations_compliance', 8)->nullable();
            $table->date('phd_registration_date')->nullable();
            $table->date('phd_submission_date')->nullable();
            $table->date('phd_award_date')->nullable();
            $table->date('phd_notification_date')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'qualification_level_id']);
        });

        Schema::create('eligibility_tests', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name', 32);          // NET, JRF, SLET, SET, GATE
            $table->string('agency')->nullable();
            $table->string('subject')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('roll_no')->nullable();
            $table->string('certificate_no')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });

        Schema::create('employment_histories', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('employer');
            $table->string('employment_type', 32)->nullable();
            $table->string('designation');
            $table->boolean('is_permanent')->default(false);
            $table->string('nature_of_appointment', 32)->nullable();
            $table->date('from');
            $table->date('to')->nullable();
            $table->text('nature_of_duties')->nullable();
            $table->string('reason_for_leaving')->nullable();

            // The F-3 shape, adopted for both forms: FN-1 still says "Scale of
            // Pay", which cannot record a 7th-CPC appointment.
            $table->string('pay_level', 50)->nullable();
            $table->string('pay_range', 100)->nullable();
            $table->string('pay_band', 50)->nullable();
            $table->string('grade_pay', 50)->nullable();
            $table->unsignedInteger('basic_pay')->nullable();
            $table->unsignedInteger('gross_pay')->nullable();

            // Computed, not typed: an experience total a candidate calculates
            // themselves is an experience total somebody has to re-check.
            $table->unsignedInteger('duration_days')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'from']);
        });

        Schema::create('referees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('mobile', 20)->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('period_known')->nullable();
            $table->timestamps();

            $table->index('user_id');
        });

        Schema::create('documents', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('collection', 48);
            $table->string('disk', 32)->default('local');
            $table->string('path');
            $table->string('original_name');
            $table->string('mime_type', 96);
            $table->unsignedInteger('size_bytes');
            $table->string('checksum', 64)->nullable();

            /*
             * Present from the first migration though only self_attested is
             * reachable in v1 (DR-005). Adding a provenance concept later
             * means re-opening submitted applications.
             */
            $table->string('provenance', 32)->default('self_attested');

            $table->timestamps();

            $table->index(['user_id', 'collection']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
        Schema::dropIfExists('referees');
        Schema::dropIfExists('employment_histories');
        Schema::dropIfExists('eligibility_tests');
        Schema::dropIfExists('academic_qualifications');
        Schema::dropIfExists('addresses');

        Schema::table('profiles', function (Blueprint $table): void {
            $table->dropColumn([
                'first_name', 'middle_name', 'last_name', 'fathers_name', 'mothers_name',
                'spouse_name', 'dob', 'gender', 'sub_caste', 'category_certificate_no',
                'category_certificate_valid_until', 'place_of_birth', 'alternate_mobile',
                'aadhaar_no', 'aadhaar_blind_index', 'identity_marks', 'is_pwd',
                'disability_percent', 'disability_certificate_authority', 'is_ex_serviceman',
                'esm_discharge_date', 'has_conviction', 'conviction_details', 'is_debarred',
                'debarment_details', 'rule_33_3_declared', 'locked',
            ]);
        });
    }
};
