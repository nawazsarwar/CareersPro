<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CentrePreference extends Model
{
    use Auditable;

    protected $table = 'centre_preferences';

    /** @var list<string> */
    protected $fillable = ['application_id', 'exam_centre_id', 'preference_order'];

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Scoped through the application, which carries both the ownership and the
     * organisational-unit answer.
     *
     * @param  Builder<CentrePreference>  $query
     * @return Builder<CentrePreference>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
