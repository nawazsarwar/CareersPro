<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Identity (M03 §2).
     *
     * The table this replaces had a nullable email, a nullable password, a
     * `verified` flag that nothing ever set to 1, and no `username`, so staff
     * could not sign in by employee ID at all.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->id();

            $table->string('name');

            // Not nullable: an account without an address cannot be verified,
            // cannot reset its password and cannot be contacted.
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();

            // DR-008. The employee ID, staff only. Nullable because applicants
            // have none, unique where present. The `@` exclusion is a
            // validation rule (M03-R04), not a column constraint: a staff
            // member whose ID contained an `@` would be routed to the email
            // branch of the resolver and would never match.
            $table->string('username')->nullable()->unique();

            $table->string('password');

            $table->string('status', 16)->default('active')->index();
            $table->boolean('must_change_password')->default(false);

            // NULL means "use the default for this user's class" from
            // config/auth_channels.php. Storing the resolved value instead
            // would freeze today's default into every row.
            $table->string('preferred_login_channel', 16)->nullable();

            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        /*
         * The authentication-facing part of the profile only.
         *
         * The full 11-section profile in domain-model.md §6 carries foreign
         * keys to categories, castes, religions, states and districts -- the
         * lookup masters M24 creates in Wave 2. The columns here are the ones
         * authentication itself depends on, and the rest arrive with the
         * tables they reference rather than as nullable placeholders.
         */
        Schema::create('profiles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();

            // S2 under data-protection.md §2: encrypted at rest, so it cannot
            // be searched. Equality lookups go through the blind index.
            $table->text('mobile')->nullable();
            $table->string('mobile_blind_index', 64)->nullable()->index();
            $table->timestamp('mobile_verified_at')->nullable();

            $table->timestamps();
        });

        Schema::create('otp_codes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Part of the lookup, so a login code can never satisfy a
            // second-factor challenge, nor the reverse (M03 §3).
            $table->string('purpose', 24);
            $table->string('channel', 16);

            $table->string('code_hash');

            // A blind index over the delivery target. It lets the hourly cap
            // be keyed on the destination without decrypting a single row.
            $table->string('destination_hash', 64)->index();

            $table->string('ip', 45)->nullable();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamp('created_at');

            $table->index(['user_id', 'purpose', 'expires_at']);
            $table->index(['destination_hash', 'created_at']);
        });

        /*
         * One row per enrolled method, replacing the TOTP-only
         * `two_factor_secrets` shape. A user may hold TOTP and SMS at once,
         * which is what makes DR-023's channel-agnostic challenge possible.
         */
        Schema::create('two_factor_methods', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('type', 16);
            $table->text('secret')->nullable();       // TOTP only, encrypted
            $table->timestamp('confirmed_at')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'type']);
        });

        Schema::create('two_factor_recovery_codes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('code_hash');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'used_at']);
        });

        // DPDP 2023 requires the notice a person consented to be identifiable
        // later, so the version is recorded rather than the fact alone.
        Schema::create('consent_records', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('notice_version', 32);
            $table->json('purposes');
            $table->string('ip', 45)->nullable();
            $table->timestamp('recorded_at');

            $table->index(['user_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consent_records');
        Schema::dropIfExists('two_factor_recovery_codes');
        Schema::dropIfExists('two_factor_methods');
        Schema::dropIfExists('otp_codes');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('users');
    }
};
