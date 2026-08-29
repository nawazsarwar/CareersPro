<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * What an advertisement declares per category.
 *
 * DR-017: no post reservation applies at AMU except for persons with
 * disability, so this records a declaration and is not a roster engine.
 */
class PostVacancyBreakup extends Model
{
    use Auditable;

    protected $table = 'post_vacancy_breakup';

    /** @var list<string> */
    protected $fillable = ['post_id', 'category', 'horizontal_category', 'count'];

    /**
     * @return BelongsTo<Post, $this>
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * @param  Builder<PostVacancyBreakup>  $query
     * @return Builder<PostVacancyBreakup>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('post', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
