<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AcademicQualification extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'academic_qualifications';

    protected $appends = [
        'document',
    ];

    protected $casts = [
        'year' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public const DIVISION_SELECT = [
        'I'   => '1st Division',
        'II'  => '2nd Division',
        'III' => '3rd Division',
        'NA'  => 'Not Applicable',
    ];

    protected $fillable = [
        'name_id',
        'course',
        'board_id',
        'year',
        'division',
        'percentage',
        'cgpa',
        'subjects',
        'title',
        'remarks',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function name()
    {
        return $this->belongsTo(QualificationLevel::class, 'name_id');
    }

    public function board()
    {
        return $this->belongsTo(Board::class, 'board_id');
    }

    public function getYearAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setYearAttribute($value)
    {
        $this->attributes['year'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getDocumentAttribute()
    {
        return $this->getMedia('document')->last();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
