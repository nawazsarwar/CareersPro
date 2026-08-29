<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * The shared base for the reference masters.
 *
 * They are university-wide reference material: they describe posts, places and
 * qualifications, not people. Restricting them by organisational subtree would
 * leave a Dean's-office user unable to read the definition of the post they
 * are scrutinising, so `visibleTo` is deliberately unrestricted here and the
 * scoping lives on the records that name people.
 *
 * Extending a base rather than repeating the trait and the scope on eighteen
 * classes is what stops the nineteenth being written without them.
 */
abstract class MasterDataModel extends Model
{
    use Auditable;

    /** @var list<string> */
    protected $fillable = ['code', 'name'];

    /**
     * @param  Builder<static>  $query
     * @return Builder<static>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
