<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleSlug;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

/**
 * The thirteen roles of security-model.md §3.1 and the permissions they hold.
 *
 * What this replaces granted the `User` role `profile_edit`,
 * `application_form_edit`, `academic_qualification_delete` and `photo_show` on
 * **every row**, which is how any signed-in candidate could read and modify any
 * other candidate's dossier. A candidate here holds permissions over their own
 * records only, and "their own" is enforced by ScopedPolicy, not by the grant.
 */
class RolePermissionSeeder extends Seeder
{
    /**
     * resource => actions.
     *
     * @var array<string, list<string>>
     */
    private const RESOURCES = [
        'profile' => ['view', 'update', 'export'],
        'document' => ['view', 'create', 'delete'],
        'application' => ['view', 'create', 'update', 'submit'],
        'advertisement' => ['view', 'create', 'update', 'publish'],
        'post' => ['view', 'create', 'update'],
        'scrutiny' => ['view', 'decide', 'raise_deficiency'],
        'order' => ['view', 'reconcile', 'refund'],
        'exam' => ['view', 'allocate', 'generate'],
        'committee' => ['view', 'constitute', 'sign_off'],
        'ruleset' => ['view', 'author', 'activate'],
        'report' => ['view', 'export'],
        'audit' => ['view', 'verify'],
        'user' => ['view', 'create', 'update', 'assign_role'],
        'role' => ['view', 'create', 'update', 'delete'],
        'master_data' => ['view', 'create', 'update'],
        'impersonation' => ['start'],
    ];

    /**
     * @var array<string, list<string>>
     */
    private const GRANTS = [
        RoleSlug::Candidate->value => [
            'profile.view', 'profile.update', 'profile.export',
            'document.view', 'document.create', 'document.delete',
            'application.view', 'application.create', 'application.update', 'application.submit',
            'order.view',
        ],
        RoleSlug::DeanOfficeAdmin->value => [
            'advertisement.view', 'advertisement.create', 'advertisement.update', 'advertisement.publish',
            'post.view', 'post.create', 'post.update',
            'application.view', 'scrutiny.view', 'report.view',
        ],
        RoleSlug::DeanOfficeScrutiny->value => [
            'advertisement.view', 'post.view', 'application.view',
            'scrutiny.view', 'scrutiny.decide', 'scrutiny.raise_deficiency',
        ],
        // Read only, deliberately: DR-015 splits the Dean's office three ways
        // so that viewing, deciding and creating are different people.
        RoleSlug::DeanOfficeView->value => [
            'advertisement.view', 'post.view', 'application.view', 'scrutiny.view',
        ],
        RoleSlug::ScrutinyOfficer->value => [
            'application.view', 'scrutiny.view', 'scrutiny.decide', 'scrutiny.raise_deficiency',
        ],
        RoleSlug::RecruitmentAdmin->value => [
            'advertisement.view', 'advertisement.create', 'advertisement.update', 'advertisement.publish',
            'post.view', 'post.create', 'post.update',
            'application.view', 'master_data.view', 'master_data.create', 'master_data.update',
            'report.view', 'report.export',
        ],
        RoleSlug::ExamAdmin->value => [
            'exam.view', 'exam.allocate', 'exam.generate', 'application.view', 'report.view',
        ],
        // No PII beyond name and application number (security-model.md §3.1),
        // which is why `profile.view` is absent.
        RoleSlug::FinanceAdmin->value => [
            'order.view', 'order.reconcile', 'order.refund', 'report.view', 'report.export',
        ],
        RoleSlug::CommitteeMember->value => [
            'committee.view', 'committee.sign_off', 'application.view',
        ],
        // Authors but cannot activate. This separation is what would have
        // stopped a fabricated ruleset reaching production.
        RoleSlug::RulesAdmin->value => [
            'ruleset.view', 'ruleset.author',
        ],
        RoleSlug::RulesVerifier->value => [
            'ruleset.view', 'ruleset.activate',
        ],
        RoleSlug::Auditor->value => [
            'audit.view', 'audit.verify', 'report.view', 'report.export',
            'application.view', 'advertisement.view',
        ],
        // Everything, and every action audited.
        RoleSlug::SuperAdmin->value => ['*'],
    ];

    public function run(): void
    {
        $permissions = [];

        foreach (self::RESOURCES as $resource => $actions) {
            foreach ($actions as $action) {
                $slug = "{$resource}.{$action}";

                $permissions[$slug] = Permission::query()->firstOrCreate(
                    ['slug' => $slug],
                    ['resource' => $resource, 'action' => $action],
                );
            }
        }

        foreach (RoleSlug::cases() as $slug) {
            $role = Role::query()->updateOrCreate(
                ['slug' => $slug->value],
                [
                    'name' => $slug->label(),
                    'is_system' => true,
                    'requires_organisational_unit' => $slug->requiresOrganisationalUnit(),
                ],
            );

            // No fallback: static analysis proves the map covers every case,
            // so a `?? []` would be dead code today and a silently unprivileged
            // role tomorrow. Adding a case without a grant fails the analysis.
            $granted = self::GRANTS[$slug->value];

            $ids = $granted === ['*']
                ? array_map(static fn (Permission $p): int => (int) $p->getKey(), $permissions)
                : array_map(
                    static fn (string $g): int => (int) $permissions[$g]->getKey(),
                    array_filter($granted, static fn (string $g): bool => isset($permissions[$g])),
                );

            $role->permissions()->sync($ids);
        }
    }
}
