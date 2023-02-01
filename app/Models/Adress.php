<?php

namespace App\Models;

use \DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adress extends Model
{
    use SoftDeletes;
    use HasFactory;

    public const TYPE_SELECT = [
        'Correspondence Address' => 'Correspondence Address',
        'Permanent Address'      => 'Permanent Address',
    ];

    public $table = 'adresses';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'type',
        'house_no',
        'street',
        'landmark',
        'locality',
        'city',
        'postal_code_id',
        'district',
        'province_id',
        'country_id',
        'status',
        'remarks',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function postal_code()
    {
        return $this->belongsTo(PostalCode::class, 'postal_code_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
