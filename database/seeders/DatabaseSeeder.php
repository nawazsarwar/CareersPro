<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Wave 0 seeds nothing.
     *
     * The seeders removed in this wave granted the `User` role row-level
     * permissions on every record — `profile_edit`, `application_form_edit`,
     * `academic_qualification_delete` — which is defect #1 in
     * docs/v3/01-design/security/security-model.md §2.
     *
     * Roles and permissions are re-seeded in Wave 1 against the authorisation
     * matrix in M25, and the lookup tables in Wave 2 against M24.
     */
    public function run(): void
    {
        //
    }
}
