<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ScoreLine extends Model
{
    use Auditable;

    protected $table = 'score_lines';

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = ['score_run_id', 'rule_id', 'citation', 'claim_id', 'raw_value', 'apportionment_factor', 'points', 'explanation'];

    /**
     * @param  Builder<ScoreLine>  $query
     * @return Builder<ScoreLine>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
