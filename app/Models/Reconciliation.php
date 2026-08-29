<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Reconciliation extends Model
{
    use Auditable;

    protected $table = 'reconciliations';

    /** @var list<string> */
    protected $fillable = ['gateway', 'file_name', 'uploaded_by_id', 'rows_total', 'rows_matched', 'rows_discrepant', 'status'];

    /**
     * Financial reference material, read by finance and by nobody else. It
     * carries no candidate personal data, so there is no subtree to scope it
     * by -- the permission alone decides.
     *
     * @param  Builder<Reconciliation>  $query
     * @return Builder<Reconciliation>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
