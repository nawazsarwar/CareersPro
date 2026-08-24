<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;

class FeeService
{
    /**
     * Calculate the fee for a given post and user based on category and PwBD status.
     * UGC / Government rules typically exempt SC/ST and PwBD candidates.
     */
    public function calculateFee(Post $post, User $user): float
    {
        $baseFee = (float) $post->fee;

        $profile = $user->profile;
        if (!$profile) {
            return $baseFee;
        }

        // PwBD Exemption
        if ($profile->pwd === 'Yes') {
            return 0.00;
        }

        // Category Exemption (Assuming Category IDs: 3=SC, 4=ST from previous stub)
        if (in_array($profile->category_id, [3, 4])) {
            return 0.00;
        }

        return $baseFee;
    }
}
