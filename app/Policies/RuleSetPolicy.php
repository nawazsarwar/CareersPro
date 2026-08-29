<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RoleSlug;
use App\Models\User;

/**
 * Separation of duties on the statutory ruleset (M25-R06, M25-R07).
 *
 * `rules_admin` authors; only `rules_verifier` activates; and the two must be
 * different people. This is the control that would have stopped a fabricated
 * ruleset -- one asserting a Principal Investigator scores 100% where the
 * Gazette says 50% -- from reaching production and making every Associate
 * Professor determination wrong.
 *
 * The ruleset model arrives in Wave 7 (M20). The policy is written here, with
 * M25, because it is an authorisation rule and this is the authorisation
 * module; M20 attaches it to the model.
 */
class RuleSetPolicy extends ScopedPolicy
{
    public function author(User $user): bool
    {
        return $this->hasPermission($user, 'ruleset.author');
    }

    /**
     * @param  object{authored_by_id?: int|null}  $ruleSetVersion
     */
    public function activate(User $user, object $ruleSetVersion): bool
    {
        if (! $this->hasPermission($user, 'ruleset.activate')) {
            return false;
        }

        // super_admin holds every permission, including both halves of this
        // one. Second-reader verification means a second reader, so the
        // author check applies to everybody.
        $authorId = $ruleSetVersion->authored_by_id ?? null;

        if ($authorId !== null && (int) $authorId === (int) $user->getKey()) {
            return false;
        }

        return $user->hasRole(RoleSlug::RulesVerifier) || $user->hasRole(RoleSlug::SuperAdmin);
    }
}
