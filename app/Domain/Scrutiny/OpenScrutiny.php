<?php

declare(strict_types=1);

namespace App\Domain\Scrutiny;

use App\Enums\LifecycleState;
use App\Models\Application;
use App\Models\User;
use Carbon\CarbonImmutable;
use RuntimeException;

final class OpenScrutiny
{
    public function handle(Application $application, User $actor): void
    {
        if ((int) $application->user_id === (int) $actor->getKey()) {
            throw new RuntimeException('An officer cannot scrutinise their own application.');
        }

        if (! $application->submitted) {
            throw new RuntimeException('A draft application cannot be scrutinised.');
        }

        if ($application->lifecycle_state === LifecycleState::UnderScrutiny) {
            return;
        }

        $from = $application->lifecycle_state;

        $application->forceFill(['lifecycle_state' => LifecycleState::UnderScrutiny])->save();

        $application->statusHistory()->create([
            'from_state' => $from->value,
            'to_state' => LifecycleState::UnderScrutiny->value,
            'actor_id' => $actor->getKey(),
            'at' => CarbonImmutable::now(),
        ]);
    }
}
