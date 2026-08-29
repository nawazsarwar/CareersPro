<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\LifecycleState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property LifecycleState $lifecycle_state
 * @property string $application_no
 * @property \Illuminate\Support\Carbon|null $submitted_at
 */
class Application extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\ApplicationFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'application_no', 'user_id', 'post_id', 'advertisement_id',
        'applied_under_category', 'applied_under_horizontal_category',
        'is_internal_candidate', 'lifecycle_state',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'lifecycle_state' => LifecycleState::class,
            'submitted' => 'boolean',
            'paid' => 'boolean',
            'is_internal_candidate' => 'boolean',
            'submitted_at' => 'datetime',
            'archived_at' => 'datetime',
            'withdrawn_at' => 'datetime',
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
     * @return BelongsTo<Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @return HasMany<EligibilityDecision, $this>
     */
    public function eligibilityDecisions(): HasMany
    {
        // Ordered by insertion, which is process order: scrutiny, then
        // written test, then interview. An unordered relation would render
        // the gates in whatever order the database returned them, and a
        // three-stage decision panel out of sequence invites the wrong one
        // being filled in.
        return $this->hasMany(EligibilityDecision::class)->orderBy('id');
    }

    /**
     * @return HasMany<ApplicationSnapshot, $this>
     */
    public function snapshots(): HasMany
    {
        return $this->hasMany(ApplicationSnapshot::class);
    }

    /**
     * @return HasMany<ApplicationStatusHistory, $this>
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(ApplicationStatusHistory::class);
    }

    /**
     * @return HasMany<Deficiency, $this>
     */
    public function deficiencies(): HasMany
    {
        return $this->hasMany(Deficiency::class);
    }

    /**
     * Both scopes. A candidate reaches their own applications; staff reach
     * those inside their organisational subtree, resolved through the post's
     * snapshot rather than a live join.
     *
     * @param  Builder<Application>  $query
     * @return Builder<Application>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        if (! $user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        $paths = app(\App\Domain\Access\ResolveScopes::class)->for($user);

        // A candidate: ownership only.
        if ($paths === []) {
            return $query->where('user_id', $user->getKey());
        }

        if ($paths === null) {
            return $query;
        }

        return $query->whereHas('post', function (Builder $inner) use ($paths): void {
            $inner->where(function (Builder $scoped) use ($paths): void {
                foreach ($paths as $path) {
                    $scoped->orWhere('ou_path_snapshot', 'like', $path.'%');
                }
            });
        });
    }
}
