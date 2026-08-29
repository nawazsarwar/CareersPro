<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Support\Crypto\BlindIndex;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string|null $mobile
 * @property string|null $mobile_blind_index
 * @property \Illuminate\Support\Carbon|null $mobile_verified_at
 */
class Profile extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = ['user_id', 'mobile', 'mobile_verified_at'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mobile' => 'encrypted',
            'mobile_verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // The index is derived, never supplied: keeping it in one place is what
        // stops a caller writing a mobile number without one and quietly
        // escaping the per-destination OTP cap.
        static::saving(static function (Profile $profile): void {
            $profile->mobile_blind_index = $profile->mobile === null
                ? null
                : BlindIndex::of($profile->mobile);
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hasVerifiedMobile(): bool
    {
        return $this->mobile !== null && $this->mobile_verified_at !== null;
    }

    /**
     * Masked wherever it is named, so a shoulder-surfer learns nothing and the
     * user still recognises their own handset (M03 §7).
     */
    public function maskedMobile(): ?string
    {
        if ($this->mobile === null) {
            return null;
        }

        return '•••••• '.mb_substr($this->mobile, -4);
    }
}
