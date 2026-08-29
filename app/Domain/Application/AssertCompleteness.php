<?php

declare(strict_types=1);

namespace App\Domain\Application;

use App\Models\Post;
use App\Models\User;

/**
 * What is still missing before this dossier can be submitted.
 *
 * Returns the list rather than throwing on the first gap, so the candidate is
 * shown everything they need to fix in one pass instead of discovering it one
 * field at a time.
 */
final class AssertCompleteness
{
    /**
     * @return list<string>
     */
    public function check(User $user, Post $post): array
    {
        $missing = [];
        $profile = $user->profile;

        if ($profile === null) {
            return [__('application.missing_profile')];
        }

        foreach ([
            'first_name' => 'missing_name',
            'last_name' => 'missing_name',
            'dob' => 'missing_dob',
            'gender' => 'missing_gender',
        ] as $field => $key) {
            if ($profile->getAttribute($field) === null) {
                $missing[__('application.'.$key)] = __('application.'.$key);
            }
        }

        if (! $profile->hasVerifiedMobile()) {
            $missing['mobile'] = __('application.missing_mobile');
        }

        if (! $user->hasVerifiedEmail()) {
            $missing['email'] = __('application.missing_email');
        }

        if ($user->academicQualifications()->count() === 0) {
            $missing['qualification'] = __('application.missing_qualification');
        }

        // A benchmark disability claim without its certificate is a claim
        // scrutiny cannot verify, and the relaxation depends on it.
        if ($profile->is_pwd && $profile->disability_certificate_authority === null) {
            $missing['disability'] = __('application.missing_disability_certificate');
        }

        return array_values($missing);
    }
}
