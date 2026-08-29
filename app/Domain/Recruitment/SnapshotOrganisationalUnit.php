<?php

declare(strict_types=1);

namespace App\Domain\Recruitment;

use App\Models\Advertisement;
use App\Models\OrganisationalUnit;
use App\Models\Post;

/**
 * Denormalises the organisational unit onto the record at publish (DR-009).
 *
 * Two reasons, and both matter. A department renamed or re-parented in 2028
 * must not silently rewrite a 2026 advertisement -- the record should say what
 * it said. And the authorisation scope reads the snapshot rather than a live
 * join, so a re-parent cannot move a historical row out of the subtree of the
 * officer who decided it.
 */
final class SnapshotOrganisationalUnit
{
    public function handle(Advertisement|Post $record): void
    {
        $unitId = $record->getAttribute('organisational_unit_id');

        if ($unitId === null) {
            // General recruitment is centrally administered (DR-010) and
            // belongs to no faculty. A null snapshot is correct, and
            // ScopedPolicy reads it as "outside every subtree".
            return;
        }

        $unit = OrganisationalUnit::query()->with('type')->find($unitId);

        if ($unit === null) {
            return;
        }

        $record->forceFill([
            'ou_code_snapshot' => $unit->code,
            'ou_title_snapshot' => $unit->title,
            'ou_type_snapshot' => $unit->type?->code,
            'ou_path_snapshot' => $unit->path,
        ]);
    }
}
