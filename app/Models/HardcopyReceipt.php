<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A box of paper, tracked to its destruction.
 *
 * DR-011: nothing electronic is ever deleted. This records what happens to the
 * physical dossier -- received, stored, and after five years, for unsuccessful
 * candidates only, destroyed with an authorising officer named. Selected
 * candidates who joined are retained permanently in the central record
 * section, so they never acquire a destruction date.
 *
 * @property \Illuminate\Support\Carbon|null $destruction_due_on
 */
class HardcopyReceipt extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = [
        'application_id', 'received_at', 'storage_location', 'received_by_id',
        'admitted_late', 'postal_proof_reference', 'destruction_due_on', 'destruction_batch_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'received_at' => 'datetime',
            'destruction_due_on' => 'date',
            'admitted_late' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function isDestroyed(): bool
    {
        return $this->destruction_batch_id !== null;
    }

    /**
     * @param  Builder<HardcopyReceipt>  $query
     * @return Builder<HardcopyReceipt>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
