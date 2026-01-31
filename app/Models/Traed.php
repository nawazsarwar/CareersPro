<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Traed extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'traeds';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'teaching_at_bachelors_level_in_years',
        'teaching_at_masters_level_in_years',
        'research_at_masters_level_in_years',
        'research_at_post_doctorals_level_in_years',
        'experience_in_educational_administration_in_years',
        'any_other_administrative_experience_in_years',
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
