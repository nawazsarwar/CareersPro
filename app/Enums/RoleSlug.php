<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The thirteen roles of security-model.md §3.1.
 *
 * An enum rather than free strings because the policies name these: a typo in
 * a string comparison is an authorisation check that silently never matches,
 * and silently never matching is indistinguishable from "denied" until the day
 * it is the only check standing between two candidates' dossiers.
 */
enum RoleSlug: string
{
    case Candidate = 'candidate';
    case DeanOfficeAdmin = 'dean_office_admin';
    case DeanOfficeScrutiny = 'dean_office_scrutiny';
    case DeanOfficeView = 'dean_office_view';
    case ScrutinyOfficer = 'scrutiny_officer';
    case RecruitmentAdmin = 'recruitment_admin';
    case ExamAdmin = 'exam_admin';
    case FinanceAdmin = 'finance_admin';
    case CommitteeMember = 'committee_member';
    case RulesAdmin = 'rules_admin';
    case RulesVerifier = 'rules_verifier';
    case Auditor = 'auditor';
    case SuperAdmin = 'super_admin';

    /**
     * Roles that are meaningless without an organisational unit (DR-010,
     * DR-015). Assigning one university-wide would be the widest possible
     * failure, so it is refused at validation (M25-R12).
     */
    public function requiresOrganisationalUnit(): bool
    {
        return in_array($this, [
            self::DeanOfficeAdmin,
            self::DeanOfficeScrutiny,
            self::DeanOfficeView,
        ], true);
    }

    /**
     * The kind of scope this role carries.
     *
     * `scrutiny_officer` is deliberately OrganisationalUnit even though it may
     * be assigned university-wide: the assignment decides that, and a role
     * that CAN be scoped must never default to unscoped when its unit is
     * missing.
     */
    public function scope(): RoleScope
    {
        return match ($this) {
            self::Candidate => RoleScope::Ownership,
            self::DeanOfficeAdmin,
            self::DeanOfficeScrutiny,
            self::DeanOfficeView => RoleScope::OrganisationalUnit,
            self::ScrutinyOfficer,
            self::CommitteeMember => RoleScope::OrganisationalUnit,
            default => RoleScope::UniversityWide,
        };
    }

    public function isStaffRole(): bool
    {
        return $this !== self::Candidate;
    }

    public function label(): string
    {
        return match ($this) {
            self::Candidate => __('access.role_candidate'),
            self::DeanOfficeAdmin => __('access.role_dean_office_admin'),
            self::DeanOfficeScrutiny => __('access.role_dean_office_scrutiny'),
            self::DeanOfficeView => __('access.role_dean_office_view'),
            self::ScrutinyOfficer => __('access.role_scrutiny_officer'),
            self::RecruitmentAdmin => __('access.role_recruitment_admin'),
            self::ExamAdmin => __('access.role_exam_admin'),
            self::FinanceAdmin => __('access.role_finance_admin'),
            self::CommitteeMember => __('access.role_committee_member'),
            self::RulesAdmin => __('access.role_rules_admin'),
            self::RulesVerifier => __('access.role_rules_verifier'),
            self::Auditor => __('access.role_auditor'),
            self::SuperAdmin => __('access.role_super_admin'),
        };
    }
}
