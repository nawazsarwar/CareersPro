<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Application;
use App\Models\User;

/**
 * Both scopes (M25-R01, M25-R02).
 *
 * A candidate reaches their own applications. Staff reach those inside their
 * organisational subtree. There is no path by which one candidate reaches
 * another's -- the defect this whole model exists to close.
 */
class ApplicationPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'application.view');
    }

    public function view(User $user, Application $application): bool
    {
        return $this->permits($user, 'application.view', $application);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'application.create');
    }

    /**
     * Only the owner, and only while it is still a draft.
     */
    public function update(User $user, Application $application): bool
    {
        if (! $application->lifecycle_state->isEditable()) {
            return false;
        }

        return $this->ownedBy($user, $application)
            && $this->hasPermission($user, 'application.update');
    }

    public function withdraw(User $user, Application $application): bool
    {
        return $this->ownedBy($user, $application)
            && ! $application->lifecycle_state->isTerminal();
    }

    /**
     * Scoping over an application resolves through the post's organisational
     * snapshot, which ScopedPolicy cannot read from the application row
     * itself.
     */
    protected function pathOf(\Illuminate\Database\Eloquent\Model $model): ?string
    {
        if ($model instanceof Application) {
            return $model->post?->ou_path_snapshot;
        }

        return parent::pathOf($model);
    }
}
