<?php

namespace App\Models;

use \DateTimeInterface;
use App\Traits\Auditable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    use Auditable;
    use HasFactory;

    public $table = 'posts';

    protected $dates = [
        'open_date',
        'last_date',
        'payment_last_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'advertisement_id',
        'posttype_id',
        'serial_no',
        'title',
        'slug',
        'description',
        'vacancies',
        'location',
        'pay_level',
        'pay_range',
        'fee',
        'open_date',
        'last_date',
        'payment_last_date',
        'withdrawn',
        'status',
        'remarks',
        'added_by_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function advertisement()
    {
        return $this->belongsTo(Advertisement::class, 'advertisement_id');
    }

    public function posttype()
    {
        return $this->belongsTo(PostType::class, 'posttype_id');
    }

    public function getOpenDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setOpenDateAttribute($value)
    {
        $this->attributes['open_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getLastDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setLastDateAttribute($value)
    {
        $this->attributes['last_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getPaymentLastDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setPaymentLastDateAttribute($value)
    {
        $this->attributes['payment_last_date'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function added_by()
    {
        return $this->belongsTo(User::class, 'added_by_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
