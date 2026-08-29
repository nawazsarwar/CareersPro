<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Domain\Dossier\ComputeAge;
use App\Models\Post;
use App\Models\User;
use Carbon\CarbonImmutable;

/**
 * Checks eligibility BEFORE payment (M05 §3).
 *
 * The legacy portal took the fee first and evaluated eligibility afterwards,
 * so a candidate who was never eligible paid to find out. Roughly 29% of
 * transactions failed and the refund path was manual.
 *
 * This is advisory rather than blocking: a candidate who believes a check is
 * wrong may still apply and have it examined at scrutiny. What it must never
 * do is take money from somebody it already knows is ineligible without
 * telling them first.
 */
final class PreflightEligibility
{
    public function __construct(private readonly ComputeAge $age) {}

    /**
     * @return array{eligible: bool, warnings: list<string>}
     */
    public function check(User $user, Post $post): array
    {
        $warnings = [];
        $profile = $user->profile;

        if ($profile?->dob === null) {
            $warnings[] = __('application.preflight_no_dob');
        } elseif ($post->closing_date !== null && $post->age_limit !== null) {
            // The crucial date is the closing date, never today (CRR Rule 14).
            $crucial = CarbonImmutable::parse($post->closing_date->toDateString());
            $dob = CarbonImmutable::parse($profile->dob->toDateString());

            if ($this->age->exceedsLimit($dob, $crucial, $post->age_limit)) {
                $warnings[] = __('application.preflight_age', [
                    'age' => $this->age->on($dob, $crucial),
                    'limit' => $post->age_limit,
                    'date' => $crucial->format('d-m-Y'),
                ]);
            }
        }

        if ($user->academicQualifications()->count() === 0) {
            $warnings[] = __('application.preflight_no_qualification');
        }

        return ['eligible' => $warnings === [], 'warnings' => $warnings];
    }
}
