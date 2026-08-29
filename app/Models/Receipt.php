<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use Auditable;

    protected $table = 'receipts';

    /** @var list<string> */
    protected $fillable = ['order_id', 'receipt_no', 'issued_at'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['issued_at' => 'datetime'];
    }

    /**
     * Financial reference material, read by finance and by nobody else. It
     * carries no candidate personal data, so there is no subtree to scope it
     * by -- the permission alone decides.
     *
     * @param  Builder<Receipt>  $query
     * @return Builder<Receipt>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
