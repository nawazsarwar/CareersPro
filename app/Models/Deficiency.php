<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property \Illuminate\Support\Carbon|null $rectification_window_closes_at
 * @property \Illuminate\Support\Carbon|null $rectified_at
 */
class Deficiency extends Model
{
    use Auditable;

    protected $table = 'deficiencies';

    /** @var list<string> */
    protected $fillable = [
        'application_id', 'raised_by_id', 'raised_at', 'field_reference',
        'description', 'rectification_window_closes_at', 'rectified_at',
        'rectified_by_id', 'resolution',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'raised_at' => 'datetime',
            'rectification_window_closes_at' => 'datetime',
            'rectified_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function isOpen(): bool
    {
        return $this->rectified_at === null;
    }

    public function windowHasClosed(): bool
    {
        return $this->rectification_window_closes_at !== null
            && $this->rectification_window_closes_at->isPast();
    }

    /**
     * @param  Builder<Deficiency>  $query
     * @return Builder<Deficiency>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
