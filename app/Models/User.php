<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Domain\Audit\ProvidesAuditRole;
use App\Enums\AuthFactor;
use App\Enums\LoginChannel;
use App\Enums\UserStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $email
 * @property string|null $username
 * @property UserStatus $status
 * @property LoginChannel|null $preferred_login_channel
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property-read Profile|null $profile
 * @property-read \Illuminate\Database\Eloquent\Collection<int, TwoFactorMethod> $twoFactorMethods
 */
class User extends Authenticatable implements MustVerifyEmailContract, ProvidesAuditRole
{
    // M26-R08: every model, including this one. The trait this replaces was
    // applied to 27 of 34 models and omitted User, Role and Permission -- the
    // security-sensitive models were precisely the unaudited ones.
    use Auditable;
    use HasApiTokens;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    use Notifiable;
    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = ['name', 'email', 'username', 'password', 'preferred_login_channel'];

    /** @var list<string> */
    protected $hidden = ['password', 'remember_token'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'status' => UserStatus::class,
            'preferred_login_channel' => LoginChannel::class,
            'must_change_password' => 'boolean',
        ];
    }

    /**
     * @return HasOne<Profile, $this>
     */
    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * @return HasMany<TwoFactorMethod, $this>
     */
    public function twoFactorMethods(): HasMany
    {
        return $this->hasMany(TwoFactorMethod::class);
    }

    /**
     * @return HasMany<TwoFactorRecoveryCode, $this>
     */
    public function recoveryCodes(): HasMany
    {
        return $this->hasMany(TwoFactorRecoveryCode::class);
    }

    /**
     * @return HasMany<ConsentRecord, $this>
     */
    public function consentRecords(): HasMany
    {
        return $this->hasMany(ConsentRecord::class);
    }

    /**
     * @return HasMany<OtpCode, $this>
     */
    public function otpCodes(): HasMany
    {
        return $this->hasMany(OtpCode::class);
    }

    /**
     * A staff account is one holding an employee ID.
     *
     * Wave 1 has no roles yet; M25 replaces this with the role check, and the
     * single call site is why that is a one-line change rather than a hunt.
     */
    public function isStaff(): bool
    {
        return $this->username !== null;
    }

    public function userClass(): string
    {
        return $this->isStaff() ? 'staff' : 'candidate';
    }

    /**
     * @return list<AuthFactor>
     */
    public function confirmedFactors(): array
    {
        return $this->twoFactorMethods
            ->filter(static fn (TwoFactorMethod $method): bool => $method->isConfirmed())
            ->map(static fn (TwoFactorMethod $method): AuthFactor => $method->type)
            ->values()
            ->all();
    }

    /**
     * Recorded on every audit entry (M26 §2). Wave 1 has no roles, so this is
     * the user class; M25 replaces it with the role and its OU scope.
     */
    public function auditRole(): string
    {
        return $this->userClass();
    }

    /**
     * Required by App\Support\Table\TableQuery: a model without a visibility
     * scope is a configuration error, not a world-readable model.
     *
     * @param  Builder<User>  $query
     * @return Builder<User>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        // Wave 1: a user sees their own row. M25 widens this for the roles
        // that administer accounts, and no wider.
        $id = $user instanceof self ? $user->getKey() : null;

        return $query->where('id', $id);
    }
}
