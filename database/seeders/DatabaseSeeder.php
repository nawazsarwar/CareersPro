<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seeds what the system cannot run without.
     *
     * The seeders removed in this wave granted the `User` role row-level
     * permissions on every record — `profile_edit`, `application_form_edit`,
     * `academic_qualification_delete` — which is defect #1 in
     * docs/v3/01-design/security/security-model.md §2.
     *
     * The lookup tables are populated rather than merely created: every one
     * of them was empty after seeding in the previous build, so every dropdown
     * in the system rendered blank.
     */
    public function run(): void
    {
        // Roles and permissions are structural: the policies name these slugs,
        // so an environment without them is an environment where every
        // authorisation check silently denies.
        $this->call([
            RolePermissionSeeder::class,
            MasterDataSeeder::class,
            DesignationSeeder::class,
        ]);
    }
}
