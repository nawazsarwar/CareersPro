<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class GrievanceMessage extends Model
{
    use Auditable;

    protected $table = 'grievance_messages';

    /** @var list<string> */
    protected $fillable = ['grievance_id', 'author_id', 'body', 'is_internal'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['is_internal' => 'boolean'];
    }

    /**
     * @param  Builder<GrievanceMessage>  $query
     * @return Builder<GrievanceMessage>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
