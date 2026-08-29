<?php

declare(strict_types=1);

namespace App\Domain\Eligibility;

use App\Enums\EligibilityGate;
use App\Models\Post;

/**
 * Which gates this post actually has (M34 §3).
 *
 * Derived from the selection method, never assumed to be all three. The legacy
 * modal enabled every gate on every post type, so an officer could record a
 * written-test decision for a post with no written test -- on a decision a
 * rejected candidate can challenge.
 */
final class ActiveGates
{
    /**
     * @return list<EligibilityGate>
     */
    public function for(Post $post): array
    {
        return array_map(
            static fn (string $gate): EligibilityGate => EligibilityGate::from($gate),
            $post->activeGates(),
        );
    }

    public function includes(Post $post, EligibilityGate $gate): bool
    {
        return in_array($gate, $this->for($post), true);
    }
}
