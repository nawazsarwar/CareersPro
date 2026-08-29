<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $due_at
 */
class Grievance extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = [
        'reference', 'user_id', 'application_id', 'category', 'description',
        'status', 'due_at', 'assigned_to_id', 'resolution', 'resolved_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'due_at' => 'datetime',
            'escalated_at' => 'datetime',
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<GrievanceMessage, $this>
     */
    public function messages(): HasMany
    {
        return $this->hasMany(GrievanceMessage::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isOverdue(): bool
    {
        return $this->resolved_at === null
            && $this->due_at !== null
            && $this->due_at->isPast();
    }

    /**
     * A candidate sees their own grievances; staff with the permission see
     * all of them. A grievance is about the University's own conduct, so
     * scoping it by the faculty that is complained about would let a faculty
     * hide complaints against itself.
     *
     * @param  Builder<Grievance>  $query
     * @return Builder<Grievance>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        if (! $user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        $paths = app(\App\Domain\Access\ResolveScopes::class)->for($user);

        return $paths === [] ? $query->where('user_id', $user->getKey()) : $query;
    }
}
