<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class RuleSet extends Model
{
    use Auditable;

    protected $table = 'rule_sets';

    /** @var list<string> */
    protected $fillable = ['slug', 'title', 'applies_to', 'design_doc'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['applies_to' => 'array'];
    }

    /**
     * @param  Builder<RuleSet>  $query
     * @return Builder<RuleSet>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
