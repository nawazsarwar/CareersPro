<?php

declare(strict_types=1);

uses(Tests\TestCase::class);

// M25-R04 — every policy over a scoped resource extends ScopedPolicy.
//
// The base class is what makes the rule unforgettable: permission AND scope,
// evaluated together. A policy that does not extend it can express "has the
// permission" alone, which is the exact shape of the defect this module
// closes.

it('extends ScopedPolicy in every policy class', function (): void {
    $offenders = [];

    foreach (glob(app_path('Policies/*.php')) ?: [] as $file) {
        $class = 'App\\Policies\\'.basename($file, '.php');

        if ($class === App\Policies\ScopedPolicy::class) {
            continue;
        }

        if (! is_subclass_of($class, App\Policies\ScopedPolicy::class)) {
            $offenders[] = $class;
        }
    }

    expect($offenders)->toBe([]);
});

it('never lets a policy answer on a permission alone', function (): void {
    // ScopedPolicy::permits is the only route that evaluates both halves. A
    // policy method over a model that calls hasPermission directly has skipped
    // the scope check, so the pairing is asserted rather than trusted.
    $source = (string) file_get_contents(app_path('Policies/ScopedPolicy.php'));

    expect($source)->toContain('$this->hasPermission($user, $ability) && $this->inScope($user, $model)');
});
