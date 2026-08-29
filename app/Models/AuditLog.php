<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A read model. There is no create, update or delete path here by design
 * (M26 §3): entries are written by App\Domain\Audit\RecordAuditEvent inside a
 * transaction that also allocates the sequence, and the database refuses
 * UPDATE and DELETE regardless.
 *
 * @property int $sequence
 * @property string $previous_hash
 * @property string $hash
 * @property string $event
 * @property string|null $subject_type
 * @property int|null $subject_id
 * @property int|null $actor_id
 * @property int|null $impersonator_id
 * @property string|null $actor_ip
 * @property string|null $actor_role
 * @property array<string, mixed> $properties
 * @property \Carbon\CarbonImmutable $occurred_at
 */
class AuditLog extends Model
{
    public const GENESIS_HASH = '0000000000000000000000000000000000000000000000000000000000000000';

    protected $table = 'audit_logs';

    public $timestamps = false;

    /**
     * Microsecond precision, matching the `timestamp(6)` column.
     *
     * Eloquent's default `Y-m-d H:i:s` would truncate on write while
     * `occurred_at` is part of the hash, so every entry would fail its own
     * verification. The column, the hash and this format have to agree.
     */
    protected $dateFormat = 'Y-m-d H:i:s.u';

    /** @var list<string> */
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'occurred_at' => 'immutable_datetime',
            'sequence' => 'integer',
        ];
    }

    /**
     * Belt and braces alongside the database triggers. The triggers are the
     * real guarantee; this makes the intent visible at the call site and turns
     * an accidental ->save() into a clear error rather than a driver exception.
     */
    public function save(array $options = []): bool
    {
        if ($this->exists) {
            throw new \LogicException('audit_logs is append-only: an existing entry cannot be saved.');
        }

        return parent::save($options);
    }

    public function delete(): bool
    {
        throw new \LogicException('audit_logs is append-only: an entry cannot be deleted.');
    }
}
