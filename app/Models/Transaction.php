<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One attempt at settling an order.
 *
 * `gateway_payload` holds what the gateway returned, minus anything
 * instrument-shaped: no card data is stored, not masked and not hashed.
 */
class Transaction extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = [
        'order_id', 'gateway_txn_id', 'status', 'amount_paise',
        'method', 'gateway_payload', 'occurred_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gateway_payload' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Order, $this>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * @param  Builder<Transaction>  $query
     * @return Builder<Transaction>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('order', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
