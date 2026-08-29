<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

/**
 * Scrutiny is a different question from viewing (M18 §6).
 *
 * DR-015 splits the Dean's office three ways precisely so that reading a
 * dossier, deciding a gate and creating an advertisement are different people.
 * A single "can access scrutiny" permission would collapse that back.
 */
class ScrutinyPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'scrutiny.view');
    }

    public function scrutinise(User $user, Application $application): bool
    {
        // Never one's own application, whatever the permission says.
        if ((int) $application->user_id === (int) $user->getKey()) {
            return false;
        }

        return $this->permits($user, 'scrutiny.view', $application);
    }

    public function decideGate(User $user, Application $application): bool
    {
        if ((int) $application->user_id === (int) $user->getKey()) {
            return false;
        }

        return $this->permits($user, 'scrutiny.decide', $application);
    }

    public function raiseDeficiency(User $user, Application $application): bool
    {
        return $this->permits($user, 'scrutiny.raise_deficiency', $application);
    }

    protected function pathOf(\Illuminate\Database\Eloquent\Model $model): ?string
    {
        if ($model instanceof Application) {
            return $model->post?->ou_path_snapshot;
        }

        return parent::pathOf($model);
    }
}
