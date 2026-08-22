<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmploymentHistory extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'employment_histories';

    protected $dates = [
        'from',
        'to',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TYPE_SELECT = [
        'Government' => 'Government',
        'Private'    => 'Private',
        'Other'      => 'Other',
    ];

    protected $fillable = [
        'employer',
        'type',
        'designation',
        'from',
        'to',
        'responsibilities',
        'reason_for_leaving',
        'pay_band',
        'basic_pay',
        'gross_pay',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getFromAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setFromAttribute($value)
    {
        $this->attributes['from'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getToAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setToAttribute($value)
    {
        $this->attributes['to'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
