<?php

declare(strict_types=1);

namespace App\Domain\Audit;

use App\Enums\AuditEventName;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;

/**
 * One thing that happened, as the domain describes it.
 *
 * Deliberately a value object rather than an array: the audit record is
 * evidence, and evidence assembled from a loosely-typed array is evidence with
 * optional fields.
 */
final readonly class AuditEvent
{
    /**
     * @param  array<string, mixed>  $properties
     */
    public function __construct(
        public AuditEventName $event,
        public array $properties = [],
        public ?Model $subject = null,
        public ?int $actorId = null,
        public ?int $impersonatorId = null,
        public ?string $actorIp = null,
        public ?string $actorRole = null,
        public ?CarbonImmutable $occurredAt = null,
    ) {}

    /**
     * The morph alias, never the class name. A namespace change must not
     * orphan six years of records.
     */
    public function subjectType(): ?string
    {
        return $this->subject?->getMorphClass();
    }

    public function subjectId(): ?int
    {
        $key = $this->subject?->getKey();

        return $key === null ? null : (int) $key;
    }
}
