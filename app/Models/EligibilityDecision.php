<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\EligibilityGate;
use App\Enums\GateDecision;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property EligibilityGate $gate
 * @property GateDecision|null $decision
 */
class EligibilityDecision extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = ['application_id', 'gate', 'decision', 'remark', 'decided_by_id', 'decided_at'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gate' => EligibilityGate::class,
            'decision' => GateDecision::class,
            'decided_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function isPending(): bool
    {
        return $this->decision === null;
    }

    /**
     * @param  Builder<EligibilityDecision>  $query
     * @return Builder<EligibilityDecision>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
