<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeatAllocation extends Model
{
    use Auditable;

    protected $table = 'seat_allocations';

    /** @var list<string> */
    protected $fillable = ['application_id', 'post_id', 'exam_centre_id', 'room_no', 'seat_no', 'allocation_rule'];

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
     * @param  Builder<SeatAllocation>  $query
     * @return Builder<SeatAllocation>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
