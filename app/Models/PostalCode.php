<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostalCode extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'postal_codes';

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'locality',
        'code',
        'sub_district',
        'district',
        'province_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
