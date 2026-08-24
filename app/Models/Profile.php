<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'profiles';

    protected $dates = [
        'dob',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'father_name',
        'mother_name',
        'gender',
        'dob',
        'nationality',
        'domicile_id',
        'marital_status_id',
        'category_id',
        'caste_id',
        'religion_id',
        'is_pwd',
        'disability_type_id',
        'disability_percentage',
        'ex_serviceman',
        'govt_id_type',
        'govt_id_number',
        'blood_group',
        'user_id',
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
}
