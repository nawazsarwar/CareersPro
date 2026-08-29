<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * The dossier exactly as it stood at a moment that mattered.
 *
 * Append-only, and the database enforces it. "View this application exactly as
 * it was scored on date X" is what CRR Rule 22.4 and an RTI request both ask,
 * and a table that can be updated cannot answer either.
 *
 * @property array<string, mixed> $payload
 * @property string $content_hash
 */
class ApplicationSnapshot extends Model
{
    use Auditable;

    public $timestamps = false;

    protected $dateFormat = 'Y-m-d H:i:s.u';

    /** @var list<string> */
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'taken_at' => 'immutable_datetime',
        ];
    }

    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new LogicException('application_snapshots is append-only: an existing snapshot cannot be saved.');
        }

        return parent::save($options);
    }

    /**
     * @return BelongsTo<Application, $this>
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * @param  Builder<ApplicationSnapshot>  $query
     * @return Builder<ApplicationSnapshot>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query->whereHas('application', static fn (Builder $q) => $q->scopes(['visibleTo' => [$user]]));
    }
}
