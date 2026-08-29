<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A published, dated change to a published advertisement.
 *
 * Corrigenda are objects rather than edits because candidates have already
 * read and relied on what the advertisement said. The legacy system appended a
 * unix timestamp to the slug as a de-duplication hack and kept no record of
 * what changed.
 *
 * @property array<string, mixed>|null $changes
 */
class Corrigendum extends Model
{
    use Auditable;

    protected $table = 'corrigenda';

    /** @var list<string> */
    protected $fillable = ['advertisement_id', 'corrigendum_no', 'issued_on', 'description', 'changes', 'published_at', 'issued_by_id'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'issued_on' => 'date',
            'published_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Advertisement, $this>
     */
    public function advertisement(): BelongsTo
    {
        return $this->belongsTo(Advertisement::class);
    }

    /**
     * @param  Builder<Corrigendum>  $query
     * @return Builder<Corrigendum>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        // scopes() rather than the magic ->visibleTo(): inside whereHas the
        // closure receives a generic Builder, and the dynamic scope call is
        // invisible to static analysis there.
        return $query->whereHas('advertisement', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
