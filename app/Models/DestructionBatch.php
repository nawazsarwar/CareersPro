<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class DestructionBatch extends Model
{
    use Auditable;

    protected $table = 'destruction_batches';

    /** @var list<string> */
    protected $fillable = ['reference', 'destroyed_on', 'authorised_by_id', 'dossier_count', 'note'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['destroyed_on' => 'date'];
    }

    /**
     * @param  Builder<DestructionBatch>  $query
     * @return Builder<DestructionBatch>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
