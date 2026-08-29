<?php

declare(strict_types=1);

namespace App\Domain\Audit;

use App\Enums\AuditEventName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Applied to every model (M26-R08), not to 27 of 34.
 *
 * The trait it replaces omitted User, Role, Permission and ResearchPublication
 * -- the security-sensitive models were precisely the unaudited ones -- and had
 * no hooks for restore or force-delete.
 *
 * @mixin Model
 */
trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(static function (Model $model): void {
            self::audit(AuditEventName::ModelCreated, $model, ['attributes' => $model->getAttributes()]);
        });

        static::updated(static function (Model $model): void {
            $changes = $model->getChanges();
            unset($changes['updated_at']);

            if ($changes === []) {
                return;
            }

            self::audit(AuditEventName::ModelUpdated, $model, [
                'changed' => array_keys($changes),
                'from' => array_intersect_key($model->getOriginal(), $changes),
                'to' => $changes,
            ]);
        });

        static::deleted(static function (Model $model): void {
            self::audit(AuditEventName::ModelDeleted, $model, [
                'soft' => method_exists($model, 'trashed'),
            ]);
        });

        // Both hooks the previous trait lacked. A restore is a state change
        // like any other, and a force-delete is the one event that most needs
        // a record precisely because the row itself will be gone.
        if (method_exists(static::class, 'restored')) {
            static::restored(static function (Model $model): void {
                self::audit(AuditEventName::ModelRestored, $model);
            });
        }

        if (method_exists(static::class, 'forceDeleted')) {
            static::forceDeleted(static function (Model $model): void {
                self::audit(AuditEventName::ModelDeleted, $model, ['forced' => true]);
            });
        }
    }

    /**
     * @param  array<string, mixed>  $properties
     */
    private static function audit(AuditEventName $event, Model $model, array $properties = []): void
    {
        $user = Auth::user();

        app(RecordAuditEvent::class)->handle(new AuditEvent(
            event: $event,
            properties: $properties,
            subject: $model,
            actorId: $user?->getAuthIdentifier() === null ? null : (int) $user->getAuthIdentifier(),
            impersonatorId: self::impersonatorId(),
            actorIp: Request::ip(),
            actorRole: self::actorRole(),
        ));
    }

    /**
     * M26-R10 — an impersonated action records both parties. Recording only
     * the impersonated user would attribute an administrator's action to a
     * candidate.
     */
    private static function impersonatorId(): ?int
    {
        $id = session('impersonator_id');

        return is_numeric($id) ? (int) $id : null;
    }

    private static function actorRole(): ?string
    {
        $user = Auth::user();

        // An interface rather than method_exists(): the contract is then
        // visible on the models that satisfy it, and static analysis can see
        // it too.
        return $user instanceof ProvidesAuditRole ? $user->auditRole() : null;
    }
}
