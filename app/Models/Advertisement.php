<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\AdvertisementStatus;
use App\Enums\AppointmentNature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property AdvertisementStatus $status
 * @property AppointmentNature $appointment_nature
 * @property string|null $ou_path_snapshot
 * @property \Illuminate\Support\Carbon|null $published_at
 * @property \Illuminate\Support\Carbon|null $default_closing_date
 */
class Advertisement extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\AdvertisementFactory> */
    use HasFactory;

    use SoftDeletes;

    /** @var list<string> */
    protected $fillable = [
        'advertisement_no', 'title', 'slug', 'description', 'advertisement_type_id',
        'organisational_unit_id', 'appointment_nature', 'dated', 'default_fee',
        'default_opening_date', 'default_closing_date', 'default_payment_closing_date',
        'status', 'added_by_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => AdvertisementStatus::class,
            'appointment_nature' => AppointmentNature::class,
            'dated' => 'date',
            'default_opening_date' => 'date',
            'default_closing_date' => 'date',
            'default_payment_closing_date' => 'date',
            'published_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    /**
     * An Advertisement has many Posts. The previous domain model had this
     * backwards, and the entire fee, date and eligibility model hangs off Post.
     *
     * @return HasMany<Post, $this>
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @return HasMany<Corrigendum, $this>
     */
    public function corrigenda(): HasMany
    {
        return $this->hasMany(Corrigendum::class);
    }

    /**
     * @return BelongsTo<OrganisationalUnit, $this>
     */
    public function organisationalUnit(): BelongsTo
    {
        return $this->belongsTo(OrganisationalUnit::class);
    }

    /**
     * @return BelongsTo<AdvertisementType, $this>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(AdvertisementType::class, 'advertisement_type_id');
    }

    public function isPublished(): bool
    {
        return $this->status === AdvertisementStatus::Published;
    }

    /**
     * Scoped by organisational subtree (DR-010). A Dean's-office user reaches
     * their faculty's local advertisements and no others; a central
     * administrator reaches everything.
     *
     * @param  Builder<Advertisement>  $query
     * @return Builder<Advertisement>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        if (! $user instanceof User) {
            // The public sees published advertisements only.
            return $query->whereIn('status', [
                AdvertisementStatus::Published->value,
                AdvertisementStatus::Paused->value,
                AdvertisementStatus::Closed->value,
            ]);
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
