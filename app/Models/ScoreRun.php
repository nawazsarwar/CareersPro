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
 * @property string|null $blocked_by_rule
 * @property string $input_hash
 * @property string|null $output_hash
 */
class ScoreRun extends Model
{
    use Auditable;

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = [
        'application_id', 'snapshot_id', 'rule_set_version_id', 'strategy',
        'total', 'status', 'blocked_by_rule', 'input_hash', 'output_hash',
        'is_sandbox', 'computed_at', 'computed_by_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_sandbox' => 'boolean',
            'computed_at' => 'datetime',
            'total' => 'decimal:2',
        ];
    }

    /**
     * @return HasMany<ScoreLine, $this>
     */
    public function lines(): HasMany
    {
        return $this->hasMany(ScoreLine::class);
    }

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function isBlocked(): bool
    {
        return $this->status === 'blocked';
    }

    /**
     * @param  Builder<ScoreRun>  $query
     * @return Builder<ScoreRun>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
