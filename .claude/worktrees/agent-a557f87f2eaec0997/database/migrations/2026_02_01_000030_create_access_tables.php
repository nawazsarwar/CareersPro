<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Roles, permissions and the two orthogonal scopes (M25 §2).
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug', 64)->unique();
            $table->text('description')->nullable();

            // A system role cannot be renamed or deleted through the UI: the
            // policies name these slugs, so an administrator editing one would
            // silently disable an authorisation check.
            $table->boolean('is_system')->default(false);

            // Whether the role MUST be scoped to an organisational unit. The
            // three dean_office_* roles are meaningless university-wide, and a
            // role assigned without its scope would otherwise read as
            // university-wide -- the widest possible failure (M25-R12).
            $table->boolean('requires_organisational_unit')->default(false);

            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('slug', 96)->unique();
            $table->string('resource', 48);
            $table->string('action', 32);
            $table->timestamps();

            $table->index(['resource', 'action']);
        });

        Schema::create('permission_role', function (Blueprint $table): void {
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();

            $table->primary(['permission_id', 'role_id']);
        });

        Schema::create('role_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // The second scope. NULL means university-wide; a value means that
            // unit AND its subtree, resolved through organisational_units.path.
            $table->foreignId('organisational_unit_id')->nullable()
                ->constrained('organisational_units')->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['role_id', 'user_id', 'organisational_unit_id'], 'role_user_unique');
            $table->index('user_id');
            $table->index('organisational_unit_id');
        });

        Schema::create('impersonation_tokens', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('actor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('target_id')->constrained('users')->cascadeOnDelete();
            $table->string('token_hash');
            $table->string('actor_ip', 45)->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('consumed_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();

            $table->index(['actor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('impersonation_tokens');
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
