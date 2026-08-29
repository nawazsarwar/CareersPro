<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\AppointmentNature;
use App\Enums\SelectionMethod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * A Post is an instance of a Designation, in an Organisational Unit, under an
 * Advertisement. That is what lets the rules engine bind to a stable entity
 * rather than to a free-text title.
 *
 * @property AppointmentNature $appointment_nature
 * @property SelectionMethod|null $selection_method
 * @property int|null $age_limit
 * @property \Illuminate\Support\Carbon|null $closing_date
 */
class Post extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;

    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'advertisement_id', 'post_type_id', 'designation_id', 'organisational_unit_id',
        'serial_no', 'title', 'subject', 'slug', 'appointment_nature', 'tenure_months',
        'vacancies', 'location', 'pay_level', 'pay_range', 'fee',
        'opening_date', 'closing_date', 'payment_closing_date',
        'age_limit', 'min_experience_months', 'selection_method',
        'admit_card_opening_date', 'admit_card_closing_date',
        'interview_letter_opening_date', 'interview_letter_closing_date',
        'test_date', 'interview_date', 'interview_venue', 'status', 'remark', 'withdrawn',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'appointment_nature' => AppointmentNature::class,
            'selection_method' => SelectionMethod::class,
            'opening_date' => 'date',
            'closing_date' => 'date',
            'payment_closing_date' => 'date',
            'admit_card_opening_date' => 'datetime',
            'admit_card_closing_date' => 'datetime',
            'interview_letter_opening_date' => 'datetime',
            'interview_letter_closing_date' => 'datetime',
            'test_date' => 'datetime',
            'interview_date' => 'datetime',
            'withdrawn' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Advertisement, $this>
     */
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    /**
     * @return BelongsTo<Designation, $this>
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class);
    }

    /**
     * @return BelongsTo<PostType, $this>
     */
    public function postType(): BelongsTo
    {
        return $this->belongsTo(PostType::class);
    }

    /**
     * @return HasMany<PostVacancyBreakup, $this>
     */
    public function vacancyBreakup(): HasMany
    {
        return $this->hasMany(PostVacancyBreakup::class);
    }

    /**
     * The gates that apply, driven by the post's own selection method where it
     * has one and by its type otherwise. Never all three regardless.
     *
     * @return list<string>
     */
    public function activeGates(): array
    {
        return ($this->selection_method ?? $this->postType->default_selection_method)->activeGates();
    }

    public function isOpen(): bool
    {
        return ! $this->withdrawn
            && $this->closing_date !== null
            && $this->closing_date->endOfDay()->isFuture()
            && $this->advertisement->isPublished();
    }

    /**
     * @param  Builder<Post>  $query
     * @return Builder<Post>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        if (! $user instanceof User) {
            return $query->whereHas('advertisement', static fn (Builder $q) => $q->scopes(['visibleTo' => [null]]));
        }

        $paths = app(\App\Domain\Access\ResolveScopes::class)->for($user);

        if ($paths === null) {
            return $query;
        }

        if ($paths === []) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function (Builder $inner) use ($paths): void {
            foreach ($paths as $path) {
                $inner->orWhere('ou_path_snapshot', 'like', $path.'%');
            }
        });
    }
}
