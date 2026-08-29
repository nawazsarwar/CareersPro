<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property OrderStatus $status
 * @property string $idempotency_key
 * @property int $amount_paise
 */
class Order extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'order_uid', 'application_id', 'user_id', 'idempotency_key',
        'amount_paise', 'currency', 'gateway', 'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
            'initiated_at' => 'datetime',
            'settled_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * @return HasMany<Transaction, $this>
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Finance sees every order; a candidate sees their own.
     *
     * security-model.md §3.1 gives finance_admin no PII beyond name and
     * application number, which is why the finance screens read from here and
     * never from the profile.
     *
     * @param  Builder<Order>  $query
     * @return Builder<Order>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        if (! $user instanceof User) {
            return $query->whereRaw('1 = 0');
        }

        $paths = app(\App\Domain\Access\ResolveScopes::class)->for($user);

        if ($paths === null) {
            return $query;
        }

        return $query->where('user_id', $user->getKey());
    }
}
