<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Models\Application;

/**
 * The canonical form of a dossier at a moment in time.
 *
 * Deliberately a plain array assembled here rather than a model serialisation:
 * adding a relation to a model must not silently change the shape of every
 * historical snapshot's hash. What goes in is chosen, and changing the choice
 * is a visible edit to this file.
 */
final class BuildSnapshotPayload
{
    /**
     * @return array<string, mixed>
     */
    public function for(Application $application): array
    {
        $user = $application->user;
        $profile = $user->profile;

        return [
            'application' => [
                'application_no' => $application->application_no,
                'post_id' => (int) $application->post_id,
                'advertisement_id' => (int) $application->advertisement_id,
                'applied_under_category' => $application->applied_under_category,
                'applied_under_horizontal_category' => $application->applied_under_horizontal_category,
                'is_internal_candidate' => (bool) $application->is_internal_candidate,
            ],
            'candidate' => [
                'name' => $user->name,
                'email' => $user->email,
                // Deliberately excluded: Aadhaar and mobile are S2 under
                // data-protection.md §2 and are not needed to reconstruct a
                // scoring decision. A snapshot is evidence of what was scored,
                // not a second copy of the identity columns.
                'dob' => $profile?->dob?->toDateString(),
                'gender' => $profile?->gender,
                'category_id' => $profile?->category_id,
                'is_pwd' => (bool) $profile?->is_pwd,
                'disability_percent' => $profile?->disability_percent,
                'is_ex_serviceman' => (bool) $profile?->is_ex_serviceman,
            ],
            'qualifications' => $user->academicQualifications()
                ->orderBy('id')
                ->get()
                ->map(static fn ($q): array => [
                    'level_id' => (int) $q->qualification_level_id,
                    'course' => $q->course,
                    'year_of_passing' => $q->year_of_passing,
                    'percentage' => $q->percentage === null ? null : (float) $q->percentage,
                    'cgpa' => $q->cgpa === null ? null : (float) $q->cgpa,
                    'cgpa_scale' => $q->cgpa_scale === null ? null : (float) $q->cgpa_scale,
                    'phd_regulations_compliance' => $q->phd_regulations_compliance,
                    'phd_award_date' => $q->phd_award_date?->toDateString(),
                ])
                ->all(),
            'eligibility_tests' => $user->eligibilityTests()
                ->orderBy('id')
                ->get()
                ->map(static fn ($t): array => [
                    'name' => $t->name,
                    'subject' => $t->subject,
                    'year' => $t->year,
                ])
                ->all(),
            'employment' => $user->employmentHistories()
                ->orderBy('from')
                ->get()
                ->map(static fn ($e): array => [
                    'employer' => $e->employer,
                    'designation' => $e->designation,
                    'is_permanent' => (bool) $e->is_permanent,
                    'from' => $e->from?->toDateString(),
                    'to' => $e->to?->toDateString(),
                    'duration_days' => $e->duration_days,
                ])
                ->all(),
        ];
    }
}
