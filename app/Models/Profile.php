<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'profiles';

    public const PWD_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const DEBARRED_RADIO = [
        'Yes' => 'Yes',
        'No'  => 'No',
    ];

    public const VIGILANCE_RADIO = [
        'Yes' => 'Yes',
        'No'  => 'No',
    ];

    public const CONVICTION_RADIO = [
        'Yes' => 'Yes',
        'No'  => 'No',
    ];

    public const PLACE_OF_BIRTH_SELECT = [
        'Rural' => 'Rural',
        'Urban' => 'Urban',
    ];

    protected $dates = [
        'dob',
        'mobile_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const GENDER_SELECT = [
        'Female'      => 'Female',
        'Male'        => 'Male',
        'Transgender' => 'Transgender',
        'Other'       => 'Other',
    ];

    public static $searchable = [
        'first_name',
        'middle_name',
        'last_name',
        'spouse_name',
        'fathers_name',
        'mothers_name',
        'mobile',
        'alternate_mobile',
    ];

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'spouse_name',
        'marital_status_id',
        'fathers_name',
        'mothers_name',
        'dob',
        'gender',
        'mobile',
        'mobile_verified_at',
        'alternate_mobile',
        'pwd',
        'disability_type_id',
        'disability_percent',
        'aadhaar_no',
        'religion_id',
        'category_id',
        'caste_id',
        'sub_caste',
        'nationality_id',
        'place_of_birth',
        'district_of_birth_id',
        'state_of_birth_id',
        'domicile_state_id',
        'domicile_district_id',
        'identity_marks',
        'remarks',
        'verified',
        'locked',
        'conviction',
        'conviction_reason',
        'debarred',
        'debarred_reason',
        'vigilance',
        'vigilance_reason',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function marital_status()
    {
        return $this->belongsTo(MaritalStatus::class, 'marital_status_id');
    }

    public function getDobAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getMobileVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setMobileVerifiedAtAttribute($value)
    {
        $this->attributes['mobile_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function disability_type()
    {
        return $this->belongsTo(DisabilityType::class, 'disability_type_id');
    }

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function caste()
    {
        return $this->belongsTo(Caste::class, 'caste_id');
    }

    public function nationality()
    {
        return $this->belongsTo(Country::class, 'nationality_id');
    }

    public function district_of_birth()
    {
        return $this->belongsTo(PostalCode::class, 'district_of_birth_id');
    }

    public function state_of_birth()
    {
        return $this->belongsTo(Province::class, 'state_of_birth_id');
    }

    public function domicile_state()
    {
        return $this->belongsTo(Province::class, 'domicile_state_id');
    }

    public function domicile_district()
    {
        return $this->belongsTo(PostalCode::class, 'domicile_district_id');
    }
}
