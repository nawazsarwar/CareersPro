<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \Illuminate\Support\Carbon|null $from
 * @property \Illuminate\Support\Carbon|null $to
 * @property int|null $duration_days
 */
class EmploymentHistory extends Model
{
    use Auditable;

    protected $table = 'employment_histories';

    /** @var list<string> */
    protected $fillable = [
        'user_id', 'employer', 'employment_type', 'designation', 'is_permanent',
        'nature_of_appointment', 'from', 'to', 'nature_of_duties', 'reason_for_leaving',
        'pay_level', 'pay_range', 'pay_band', 'grade_pay', 'basic_pay', 'gross_pay',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'from' => 'date',
            'to' => 'date',
            'is_permanent' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        // Computed, never typed. An experience total a candidate calculates
        // themselves is a total somebody has to re-check, and the arithmetic
        // decides eligibility.
        static::saving(static function (EmploymentHistory $row): void {
            $row->duration_days = $row->from === null
                ? null
                : (int) $row->from->diffInDays($row->to ?? now());
        });
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param  Builder<EmploymentHistory>  $query
     * @return Builder<EmploymentHistory>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->where('user_id', $user instanceof User ? $user->getKey() : null);
    }
}
