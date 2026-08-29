<?php

declare(strict_types=1);

namespace App\Domain\Scoring;

use App\Models\RuleSetVersion;

/**
 * Joint authorship and joint projects (UGC 2018 Appendix II Table 2).
 *
 * The Gazette: two authors get 70 per cent each; more than two, the first,
 * principal or corresponding author gets 70 per cent and each other joint
 * author 30 per cent; and for a joint project the Principal Investigator and
 * Co-Investigator get **50 per cent each**.
 *
 * That last figure is the one the previous work got wrong. Its rules file
 * asserted PI 100 per cent and Co-PI 50 per cent, which would have inflated
 * every Associate Professor and Professor determination involving a joint
 * project. Three independent sources -- the Gazette, the FN-1 form and the AMU
 * Ordinances -- all say 50/50.
 */
final class Apportion
{
    /**
     * @param  array<string, mixed>  $claim
     */
    public function for(array $claim, RuleSetVersion $version): float
    {
        $role = (string) ($claim['authorship_role'] ?? 'sole');
        $coauthors = (int) ($claim['coauthor_count'] ?? 1);

        return match ($role) {
            // 50 per cent EACH. Not 100 and 50.
            'pi', 'co_pi' => (float) $version->rule('apportionment.joint_project.factor'),
            default => $this->authorship($coauthors, $role, $version),
        };
    }

    private function authorship(int $coauthors, string $role, RuleSetVersion $version): float
    {
        if ($coauthors <= 1) {
            return 1.0;
        }

        if ($coauthors === 2) {
            return (float) $version->rule('apportionment.two_authors.factor');
        }

        return in_array($role, ['first', 'principal', 'corresponding'], true)
            ? (float) $version->rule('apportionment.many_authors.lead_factor')
            : (float) $version->rule('apportionment.many_authors.joint_factor');
    }
}
