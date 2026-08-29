<?php

declare(strict_types=1);

namespace App\Models;

use App\Domain\Audit\Auditable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property array<string, mixed> $payload
 * @property string $content_hash
 * @property string $status
 * @property int|null $authored_by_id
 */
class RuleSetVersion extends Model
{
    use Auditable;

    /** @use HasFactory<\Database\Factories\RuleSetVersionFactory> */
    use HasFactory;

    /** @var list<string> */
    protected $fillable = [
        'rule_set_id', 'version', 'status', 'effective_from', 'effective_to',
        'payload', 'content_hash', 'authored_by_id',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'effective_from' => 'date',
            'effective_to' => 'date',
            'second_reader_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<RuleSet, $this>
     */
    public function ruleSet(): BelongsTo
    {
        return $this->belongsTo(RuleSet::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * The value a rule declares, or null.
     *
     * Reading through one accessor rather than array-diving at each call site
     * is what lets the ambiguity check below be unmissable.
     */
    public function rule(string $path): mixed
    {
        return data_get($this->payload, $path);
    }

    /**
     * Whether this rule is one of the six Table 2 ambiguities the Executive
     * Council has not yet ratified (DR-013).
     */
    public function isPendingRatification(string $path): bool
    {
        return data_get($this->payload, $path.'.pending_ratification') === true;
    }

    public function citationFor(string $path): string
    {
        $citation = data_get($this->payload, $path.'.citation');

        // Never a fallback to an empty string: a score line without a citation
        // is not a valid output (I4), and an empty one would satisfy the
        // column while defeating its purpose.
        return is_string($citation) && $citation !== ''
            ? $citation
            : throw new \LogicException(sprintf('Rule [%s] carries no citation.', $path));
    }

    /**
     * @param  Builder<RuleSetVersion>  $query
     * @return Builder<RuleSetVersion>
     */
    public function scopeVisibleTo(Builder $query, mixed $user): Builder
    {
        return $query;
    }
}
