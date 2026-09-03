<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * The reference masters (M24 §2).
     *
     * Every one of these was named by the previous catalogue and created by
     * none of it: the lookup tables it did define were empty after seeding, so
     * every dropdown in the system rendered blank.
     */
    public function up(): void
    {
        $this->simple('countries', ['iso2' => 2, 'iso3' => 3]);
        $this->simple('religions');
        $this->simple('marital_statuses');
        $this->simple('boards');
        $this->simple('subjects');
        $this->simple('degrees');
        $this->simple('advertisement_types');

        Schema::create('provinces', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('country_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('code', 16)->nullable();
            $table->timestamps();

            $table->unique(['country_id', 'name']);
        });

        Schema::create('districts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('province_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->unique(['province_id', 'name']);
        });

        Schema::create('postal_codes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->string('code', 10)->index();
            $table->string('office')->nullable();
            $table->timestamps();
        });

        /*
         * Vertical categories. EWS is here because the 2018 Regulations omit
         * it entirely -- it arrives from a separate OM -- and a candidate who
         * cannot declare it cannot claim the fee concession that goes with it.
         */
        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 16)->unique();
            $table->string('name');
            $table->boolean('requires_certificate')->default(false);

            // OBC-NCL certificates expire; SC/ST do not. Modelling it on the
            // category is what lets validation ask for a validity date only
            // where one exists.
            $table->boolean('certificate_expires')->default(false);
            $table->timestamps();
        });

        /*
         * Horizontal categories are orthogonal to vertical ones: a candidate
         * is SC *and* a person with disability, never one instead of the
         * other. Two tables rather than one enum is the whole point.
         */
        Schema::create('horizontal_categories', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 16)->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('castes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->timestamps();

            $table->index('category_id');
        });

        // The five categories of UGC 2018 cl. 3.4 I.
        $this->simple('disability_types');

        Schema::create('qualification_levels', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name');
            $table->unsignedTinyInteger('rank');

            // Nullable under UGC 2018, REQUIRED under the 2025 draft. Present
            // from the first migration because adding it later means
            // re-opening submitted applications.
            $table->unsignedTinyInteger('ncrf_level')->nullable();
            $table->timestamps();
        });

        Schema::create('pay_levels', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 16)->unique();
            $table->string('name');
            $table->unsignedInteger('entry_pay')->nullable();
            $table->timestamps();
        });

        /*
         * Seven live rows (DR-007). The apparent duplicates are the General
         * and Local appointment regimes of DR-010, not duplicates.
         */
        Schema::create('post_types', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 32)->unique();
            $table->string('name');
            $table->string('default_selection_method', 32);

            // Which of the three eligibility gates apply to this post type.
            // The legacy modal enabled all three even on interview-only types.
            $table->boolean('has_scrutiny_gate')->default(true);
            $table->boolean('has_written_test_gate')->default(false);
            $table->boolean('has_interview_gate')->default(true);

            $table->string('submission_venue')->nullable();
            $table->timestamps();
        });

        Schema::create('exam_centres', function (Blueprint $table): void {
            $table->id();
            $table->string('code', 16)->unique();
            $table->string('name');
            $table->foreignId('district_id')->nullable()->constrained()->nullOnDelete();
            $table->text('address')->nullable();
            $table->unsignedInteger('capacity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * @param  array<string, int>  $extra  additional short string columns
     */
    private function simple(string $table, array $extra = []): void
    {
        Schema::create($table, function (Blueprint $blueprint) use ($extra): void {
            $blueprint->id();
            $blueprint->string('code', 32)->unique();
            $blueprint->string('name');

            foreach ($extra as $column => $length) {
                $blueprint->string($column, $length)->nullable();
            }

            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'exam_centres', 'post_types', 'pay_levels', 'qualification_levels',
            'disability_types', 'castes', 'horizontal_categories', 'categories',
            'postal_codes', 'districts', 'provinces', 'advertisement_types',
            'degrees', 'subjects', 'boards', 'marital_statuses', 'religions', 'countries',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
