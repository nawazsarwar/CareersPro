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
 * @property \Illuminate\Support\Carbon|null $dob
 * @property \Illuminate\Support\Carbon|null $category_certificate_valid_until
 * @property \Illuminate\Support\Carbon|null $esm_discharge_date
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $gender
 * @property string|null $aadhaar_no
 * @property string|null $aadhaar_blind_index
 * @property string|null $disability_certificate_authority
 * @property int|null $disability_percent
 * @property int|null $category_id
 * @property bool $is_pwd
 * @property bool $is_ex_serviceman
 * @property bool $locked
 */
class Profile extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = [
        'user_id', 'mobile', 'mobile_verified_at', 'alternate_mobile',
        'first_name', 'middle_name', 'last_name', 'fathers_name', 'mothers_name',
        'spouse_name', 'dob', 'gender', 'nationality_id', 'marital_status_id',
        'religion_id', 'category_id', 'caste_id', 'sub_caste',
        'category_certificate_no', 'category_certificate_valid_until',
        'place_of_birth', 'state_of_birth_id', 'domicile_state_id',
        'aadhaar_no', 'identity_marks', 'is_pwd', 'disability_type_id',
        'disability_percent', 'disability_certificate_authority',
        'is_ex_serviceman', 'esm_discharge_date', 'has_conviction',
        'conviction_details', 'is_debarred', 'debarment_details',
        'rule_33_3_declared',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'mobile' => 'encrypted',
            'mobile_verified_at' => 'datetime',
            'alternate_mobile' => 'encrypted',
            // S2 under data-protection.md §2: encrypted at rest, matched
            // through a blind index where a lookup is genuinely required.
            'aadhaar_no' => 'encrypted',
            'dob' => 'date',
            'category_certificate_valid_until' => 'date',
            'esm_discharge_date' => 'date',
            'is_pwd' => 'boolean',
            'is_ex_serviceman' => 'boolean',
            'has_conviction' => 'boolean',
            'is_debarred' => 'boolean',
            'rule_33_3_declared' => 'boolean',
            'locked' => 'boolean',
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

            // Aadhaar duplicate detection is the one lookup that must work
            // over an encrypted column (data-protection.md §2, M03-R12).
            $profile->aadhaar_blind_index = $profile->aadhaar_no === null
                ? null
                : BlindIndex::of($profile->aadhaar_no);
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
