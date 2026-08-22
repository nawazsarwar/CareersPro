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

class OtherDetail extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, Auditable, HasFactory;

    public $table = 'other_details';

    protected $dates = [
        'increment_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $appends = [
        'remark_essential_qualification_document',
        'remark_desirable_qualification_document',
    ];

    protected $fillable = [
        'user_id',
        'fellowship_undergraduate',
        'fellowship_graduate',
        'fellowship_postgraduate',
        'phd_thesis_title',
        'research_phd_awarded',
        'research_phd_thesis',
        'research_phd_total_scholars',
        'research_mphil_awarded',
        'research_mphil_thesis',
        'research_mphil_total_scholars',
        'research_other_awarded',
        'research_other_thesis',
        'research_other_total_scholars',
        'eminent_scholar',
        'contribution_to_knowledge',
        'engaged_in_research',
        'industry_experience',
        'current_pay_level',
        'current_pay_range',
        'current_basic_pay',
        'current_pay_band',
        'current_grade_pay',
        'current_basic_pay_old',
        'current_allowances',
        'current_allowances_total',
        'increment_date',
        'minimum_initial_pay',
        'joining_time',
        'books_published',
        'books_accepted',
        'papers_published',
        'papers_accepted',
        'articles_published',
        'articles_accepted',
        'papers_read_published',
        'papers_read_accepted',
        'eca_university_administration',
        'eca_student',
        'eca_residential_student',
        'eca_cultural',
        'relevant_work',
        'previous_applications',
        'testimonial_1',
        'testimonial_2',
        'testimonial_3',
        'remark_essential_qualification',
        'remark_desirable_qualification',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getIncrementDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format')) : null;
    }

    public function setIncrementDateAttribute($value)
    {
        $this->attributes['increment_date'] = $value ? Carbon::createFromFormat(config('panel.date_format'), $value)->format('Y-m-d') : null;
    }

    public function getRemarkEssentialQualificationDocumentAttribute()
    {
        return $this->getMedia('remark_essential_qualification_document')->last();
    }

    public function getRemarkDesirableQualificationDocumentAttribute()
    {
        return $this->getMedia('remark_desirable_qualification_document')->last();
    }
}
