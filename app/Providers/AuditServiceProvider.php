<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Audit\RecordAuditEvent;
use App\Domain\Audit\RedactProperties;
use App\Domain\Audit\SequenceAllocator;
use Illuminate\Support\ServiceProvider;

class AuditServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(SequenceAllocator::class, static fn ($app) => new SequenceAllocator($app['db']->connection()));

        $this->app->singleton(RedactProperties::class);

        $this->app->singleton(RecordAuditEvent::class, static fn ($app) => new RecordAuditEvent(
            $app['db']->connection(),
            $app->make(SequenceAllocator::class),
            $app->make(RedactProperties::class),
        ));
    }
}
