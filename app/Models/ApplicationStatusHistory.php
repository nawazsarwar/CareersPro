<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationStatusHistory extends Model
{
    use Auditable;

    protected $table = 'application_status_history';

    public $timestamps = false;

    /** @var list<string> */
    protected $fillable = ['application_id', 'from_state', 'to_state', 'actor_id', 'at', 'reason'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['at' => 'datetime'];
    }

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * @param  Builder<ApplicationStatusHistory>  $query
     * @return Builder<ApplicationStatusHistory>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
