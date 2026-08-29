<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use Auditable;

    protected $table = 'refunds';

    /** @var list<string> */
    protected $fillable = ['order_id', 'amount_paise', 'reason', 'gateway_refund_id', 'status', 'requested_by_id', 'completed_at'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['completed_at' => 'datetime'];
    }

    /**
     * Financial reference material, read by finance and by nobody else. It
     * carries no candidate personal data, so there is no subtree to scope it
     * by -- the permission alone decides.
     *
     * @param  Builder<Refund>  $query
     * @return Builder<Refund>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
