<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * What kind of row-level scope a role carries.
 *
 * This exists because a NULL `role_user.organisational_unit_id` means two
 * entirely different things depending on the role, and conflating them is an
 * authorisation hole rather than an inconvenience:
 *
 *   - for `recruitment_admin`, NULL means **university-wide**;
 *   - for `candidate`, NULL means **their own rows only** -- a candidate has no
 *     organisational unit and never will.
 *
 * Read as "university-wide" for a candidate, it would grant every candidate
 * every other candidate's dossier: the exact defect this module exists to
 * close, reintroduced through the scope resolver instead of the seeder.
 */
enum RoleScope
{
    case Ownership;
    case OrganisationalUnit;
    case UniversityWide;
}
