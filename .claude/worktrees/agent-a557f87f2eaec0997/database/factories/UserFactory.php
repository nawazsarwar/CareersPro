<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AuthFactor;
use App\Enums\UserStatus;
use App\Models\Profile;
use App\Models\TwoFactorMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * `status` and `email_verified_at` are deliberately absent from the
     * model's $fillable: nothing arriving over HTTP may set an account's
     * status or mark its own address verified. A factory is not HTTP, so it
     * force-fills rather than the model relaxing its guard for everyone.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function newModel(array $attributes = []): User
    {
        $user = new User;
        $user->forceFill($attributes);

        return $user;
    }

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => 'Correct-Horse-9!',
            'username' => null,
            'status' => UserStatus::Active,
            'remember_token' => Str::random(10),
        ];
    }

    public function candidate(): static
    {
        // A candidate's username is always NULL -- the invariant the
        // credential resolver depends on.
        return $this->state(fn (): array => ['username' => null]);
    }

    public function staff(): static
    {
        return $this->state(fn (): array => [
            'username' => 'EMP'.fake()->unique()->numberBetween(10000, 99999),
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (): array => ['email_verified_at' => null]);
    }

    public function suspended(): static
    {
        return $this->state(fn (): array => ['status' => UserStatus::Suspended]);
    }

    public function withProfile(): static
    {
        return $this->afterCreating(function (User $user): void {
            Profile::query()->firstOrCreate(['user_id' => $user->getKey()]);
        });
    }

    public function withVerifiedMobile(string $mobile = '9876543210'): static
    {
        return $this->afterCreating(function (User $user) use ($mobile): void {
            Profile::query()->updateOrCreate(
                ['user_id' => $user->getKey()],
                ['mobile' => $mobile, 'mobile_verified_at' => now()],
            );
        });
    }

    public function withUnverifiedMobile(string $mobile = '9876543210'): static
    {
        return $this->afterCreating(function (User $user) use ($mobile): void {
            Profile::query()->updateOrCreate(
                ['user_id' => $user->getKey()],
                ['mobile' => $mobile, 'mobile_verified_at' => null],
            );
        });
    }

    public function withTotp(string $secret = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567'): static
    {
        return $this->afterCreating(function (User $user) use ($secret): void {
            TwoFactorMethod::query()->create([
                'user_id' => $user->getKey(),
                'type' => AuthFactor::Totp,
                'secret' => $secret,
                'confirmed_at' => now(),
            ]);
        });
    }

    public function withSmsFactor(): static
    {
        return $this->afterCreating(function (User $user): void {
            TwoFactorMethod::query()->create([
                'user_id' => $user->getKey(),
                'type' => AuthFactor::Sms,
                'confirmed_at' => now(),
            ]);
        });
    }
}
