<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Advertisement;
use App\Models\User;

/**
 * Both scopes apply (M25 §3, M25-R02, M25-R03).
 *
 * A Dean's-office user reaches their faculty's local advertisements and no
 * others, and reaches no General advertisement at all: general recruitment is
 * centrally administered (DR-010) and is in no dean_office_* permission set.
 */
class AdvertisementPolicy extends ScopedPolicy
{
    public function viewAny(User $user): bool
    {
        return $this->hasPermission($user, 'advertisement.view');
    }

    public function view(User $user, Advertisement $advertisement): bool
    {
        return $this->permits($user, 'advertisement.view', $advertisement);
    }

    public function create(User $user): bool
    {
        return $this->hasPermission($user, 'advertisement.create');
    }

    public function update(User $user, Advertisement $advertisement): bool
    {
        // A published advertisement is not editable by anybody. The change is
        // a corrigendum, because candidates have already relied on what it
        // said.
        if (! $advertisement->status->isEditable()) {
            return false;
        }

        return $this->permits($user, 'advertisement.update', $advertisement);
    }

    public function publish(User $user, Advertisement $advertisement): bool
    {
        return $this->permits($user, 'advertisement.publish', $advertisement);
    }

    /**
     * Issuing a corrigendum is the publish permission, not the update one:
     * it is a public act on a published document.
     */
    public function issueCorrigendum(User $user, Advertisement $advertisement): bool
    {
        return $advertisement->isPublished()
            && $this->permits($user, 'advertisement.publish', $advertisement);
    }
}
