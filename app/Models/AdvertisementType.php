<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdvertisementType extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'advertisement_types';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const TITLE_SELECT = [
        'General' => 'General',
        'Local'   => 'Local',
    ];

    protected $fillable = [
        'title',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
