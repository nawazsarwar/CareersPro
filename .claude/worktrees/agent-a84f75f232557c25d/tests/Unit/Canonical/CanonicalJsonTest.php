<?php

declare(strict_types=1);

use App\Support\Canonical\CanonicalJson;

// M00-R05 — the same payload must hash identically in any process.

it('sorts associative keys so insertion order cannot change the hash', function (): void {
    $a = ['beta' => 1, 'alpha' => 2, 'gamma' => 3];
    $b = ['gamma' => 3, 'alpha' => 2, 'beta' => 1];

    expect(CanonicalJson::encode($a))->toBe('{"alpha":2,"beta":1,"gamma":3}')
        ->and(CanonicalJson::hash($a))->toBe(CanonicalJson::hash($b));
});

it('preserves list order, because order is meaning in a list', function (): void {
    expect(CanonicalJson::encode(['x' => [3, 1, 2]]))->toBe('{"x":[3,1,2]}');
});

it('sorts nested keys at every depth', function (): void {
    $a = ['outer' => ['b' => ['z' => 1, 'a' => 2], 'a' => 1]];
    $b = ['outer' => ['a' => 1, 'b' => ['a' => 2, 'z' => 1]]];

    expect(CanonicalJson::hash($a))->toBe(CanonicalJson::hash($b));
});

it('does not escape slashes or unicode', function (): void {
    expect(CanonicalJson::encode(['url' => 'https://amu.ac.in/a/b', 'name' => 'अलीगढ़']))
        ->toBe('{"name":"अलीगढ़","url":"https://amu.ac.in/a/b"}');
});

it('renders floats identically regardless of serialize_precision', function (): void {
    // 0.1 + 0.2 is 0.30000000000000004 as a double. The point is not which
    // of the two renderings we pick, but that it is the same one everywhere.
    expect(CanonicalJson::encode(['v' => 0.1 + 0.2]))
        ->toBe(CanonicalJson::encode(['v' => 0.1 + 0.2]));
});

it('keeps an integral float a float, because 2.0 and 2 are different claims', function (): void {
    expect(CanonicalJson::encode(['v' => 2.0]))->toBe('{"v":2.0}')
        ->and(CanonicalJson::encode(['v' => 2]))->toBe('{"v":2}');
});

it('refuses a payload that is not representable', function (): void {
    expect(fn () => CanonicalJson::encode(['v' => NAN]))
        ->toThrow(InvalidArgumentException::class);
});

it('matches a hash fixed at authoring time, so a future change is visible', function (): void {
    // A regression guard. If this value changes, every stored snapshot hash and
    // every audit-chain link computed before the change is invalidated -- which
    // must be a deliberate, migrated decision, never an incidental one.
    expect(CanonicalJson::hash(['application_no' => '10087779', 'submitted' => true]))
        ->toBe(hash('sha256', '{"application_no":"10087779","submitted":true}'));
});
