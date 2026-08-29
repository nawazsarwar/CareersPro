<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class FeeRule extends Model
{
    use Auditable;

    protected $table = 'fee_rules';

    /** @var list<string> */
    protected $fillable = ['post_id', 'advertisement_id', 'category', 'horizontal_category', 'amount_paise', 'is_exempt'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['is_exempt' => 'boolean'];
    }

    /**
     * Financial reference material, read by finance and by nobody else. It
     * carries no candidate personal data, so there is no subtree to scope it
     * by -- the permission alone decides.
     *
     * @param  Builder<FeeRule>  $query
     * @return Builder<FeeRule>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
