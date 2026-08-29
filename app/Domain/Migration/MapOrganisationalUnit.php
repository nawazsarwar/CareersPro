<?php

declare(strict_types=1);

namespace App\Domain\Migration;

use App\Models\OrganisationalUnit;

/**
 * Maps a legacy free-text post title to an organisational unit.
 *
 * Legacy `posts` carries no unit reference at all: the department lives inside
 * the title -- "Assistant Professor, Dept of Conservative Dentistry &
 * Endodontics" -- and a `location varchar(300)`. Roughly 2,874 posts need
 * mapping.
 *
 * This returns a confident match or nothing. A fuzzy guess would put a
 * historical application under the wrong faculty, and the organisational unit
 * is what the Dean-scoped authorisation reads -- so a wrong guess is not a
 * data-quality problem, it is an access-control one.
 */
final class MapOrganisationalUnit
{
    public function from(string $title, ?string $location = null): ?int
    {
        $haystack = mb_strtolower($title.' '.($location ?? ''));

        $units = OrganisationalUnit::query()
            ->where('status', 'published')
            ->get(['id', 'title', 'code']);

        $matches = [];

        foreach ($units as $unit) {
            $needle = mb_strtolower($unit->title);

            // Whole-name containment only. Substring scoring would match
            // "Physics" inside "Applied Physics" and silently pick the wrong
            // department.
            if ($needle !== '' && str_contains($haystack, $needle)) {
                $matches[] = ['id' => (int) $unit->getKey(), 'length' => mb_strlen($needle)];
            }
        }

        if ($matches === []) {
            return null;
        }

        // Ambiguous is not a match. Two candidates of the same length mean the
        // title genuinely does not distinguish them.
        usort($matches, static fn (array $a, array $b): int => $b['length'] <=> $a['length']);

        if (count($matches) > 1 && $matches[0]['length'] === $matches[1]['length']) {
            return null;
        }

        return $matches[0]['id'];
    }
}
