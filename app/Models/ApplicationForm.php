<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationForm extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'application_forms';

    public static $searchable = [
        'roll_no',
        'name',
        'email',
    ];

    public const ELIGIBLE_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const DISABILITY_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const SCRUTINIZED_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const HARDCOPY_RECEIVED_SELECT = [
        'No'  => 'No',
        'Yes' => 'Yes',
    ];

    public const GENDER_SELECT = [
        'Female'      => 'Female',
        'Male'        => 'Male',
        'Transgender' => 'Transgender',
        'Other'       => 'Other',
    ];

    protected $dates = [
        'dob',
        'eligibility_updated_at',
        'admitcard_downloaded_at',
        'interview_letter_downloaded_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'roll_no',
        'random_no',
        'advertisement_id',
        'advertisement_title',
        'post_id',
        'post_serial_no',
        'post_title',
        'name',
        'email',
        'gender',
        'dob',
        'mobile',
        'disability',
        'disability_type_id',
        'disability_percent',
        'aadhaar_no',
        'religion_id',
        'category_id',
        'caste_id',
        'sub_caste',
        'nationality_id',
        'permanent_address',
        'correspondence_address',
        'domicile_district',
        'domicile_state_id',
        'basic_details',
        'additional_details',
        'status',
        'remarks',
        'review',
        'submitted',
        'paid',
        'hardcopy_received',
        'scrutinized',
        'scrutinized_by_id',
        'scrutiny_remark',
        'eligible',
        'eligibility_remark',
        'eligibility_updated_by_id',
        'eligibility_updated_at',
        'order_uid',
        'admitcard_downloaded_at',
        'interview_letter_downloaded_at',
        'centre_name',
        'centre_code',
        'centre_address',
        'centre_city',
        'room_no',
        'seat_no',
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

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function getDobAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setDobAttribute($value)
    {
        $this->attributes['dob'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
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

    public function domicile_state()
    {
        return $this->belongsTo(Province::class, 'domicile_state_id');
    }

    public function scrutinized_by()
    {
        return $this->belongsTo(User::class, 'scrutinized_by_id');
    }

    public function eligibility_updated_by()
    {
        return $this->belongsTo(User::class, 'eligibility_updated_by_id');
    }

    public function getEligibilityUpdatedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEligibilityUpdatedAtAttribute($value)
    {
        $this->attributes['eligibility_updated_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getAdmitcardDownloadedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setAdmitcardDownloadedAtAttribute($value)
    {
        $this->attributes['admitcard_downloaded_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getInterviewLetterDownloadedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setInterviewLetterDownloadedAtAttribute($value)
    {
        $this->attributes['interview_letter_downloaded_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }
}
