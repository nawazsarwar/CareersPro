<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string|null $phd_regulations_compliance
 * @property \Illuminate\Support\Carbon|null $phd_award_date
 * @property array<string, mixed>|null $conversion_declaration
 */
class AcademicQualification extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = [
        'user_id', 'qualification_level_id', 'board_id', 'course', 'subjects',
        'year_of_passing', 'division', 'percentage', 'cgpa', 'cgpa_scale',
        'conversion_declaration', 'ncrf_level', 'phd_regulations_compliance',
        'phd_registration_date', 'phd_submission_date', 'phd_award_date',
        'phd_notification_date',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'conversion_declaration' => 'array',
            'phd_registration_date' => 'date',
            'phd_submission_date' => 'date',
            'phd_award_date' => 'date',
            'phd_notification_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<AcademicQualification>  $query
     * @return Builder<AcademicQualification>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->where('user_id', $user instanceof User ? $user->getKey() : null);
    }
}
