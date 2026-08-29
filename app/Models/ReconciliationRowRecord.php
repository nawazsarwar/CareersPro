<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ReconciliationRowRecord extends Model
{
    use Auditable;

    protected $table = 'reconciliation_rows';

    /** @var list<string> */
    protected $fillable = ['reconciliation_id', 'order_id', 'gateway_txn_id', 'gateway_status', 'local_status', 'gateway_amount_paise', 'outcome', 'note'];

    /**
     * Financial reference material, read by finance and by nobody else. It
     * carries no candidate personal data, so there is no subtree to scope it
     * by -- the permission alone decides.
     *
     * @param  Builder<ReconciliationRowRecord>  $query
     * @return Builder<ReconciliationRowRecord>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
