<?php

declare(strict_types=1);

namespace App\Domain\Access;

use App\Enums\RoleScope;
use App\Models\User;

/**
 * The organisational-unit subtrees a user may reach (DR-010).
 *
 * Returns NULL for university-wide, which is deliberately different from an
 * empty array: null means "no path filter applies", `[]` means "this user
 * reaches nothing". Collapsing the two -- returning `[]` for a central
 * administrator -- would lock out exactly the people who administer the system.
 */
final class ResolveScopes
{
    /**
     * @return list<string>|null
     */
    public function for(User $user): ?array
    {
        $assignments = $user->roles()->get();

        if ($assignments->isEmpty()) {
            return [];
        }

        $paths = [];

        foreach ($assignments as $role) {
            $slug = $role->enum();

            // A candidate has no organisational unit and never will. Their
            // NULL means "their own rows", not "every row" -- see RoleScope.
            if ($slug === null || $slug->scope() === RoleScope::Ownership) {
                continue;
            }

            $unitId = $role->pivot->organisational_unit_id ?? null;

            if ($unitId === null) {
                // A role that may be scoped but was assigned without a unit is
                // university-wide only if the role itself is. A dean_office_*
                // assignment missing its unit grants nothing rather than
                // everything; validation refuses to create one (M25-R12), and
                // this is what holds if one ever exists.
                if ($slug->scope() === RoleScope::UniversityWide) {
                    return null;
                }

                continue;
            }

            $path = \App\Models\OrganisationalUnit::query()->whereKey($unitId)->value('path');

            if (is_string($path) && $path !== '') {
                $paths[] = $path;
            }
        }

        return array_values(array_unique($paths));
    }

    public function isUniversityWide(User $user): bool
    {
        return $this->for($user) === null;
    }
}
