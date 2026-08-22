<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstitutionsAttended extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'institutions_attendeds';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'user_id',
        'name_of_school',
        'name_of_college',
        'university_board_id',
        'year_of_joining',
        'year_of_leaving',
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

    public function university_board()
    {
        return $this->belongsTo(Board::class, 'university_board_id');
    }
}
