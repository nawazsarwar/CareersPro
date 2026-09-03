<?php

declare(strict_types=1);

use App\Domain\Merit\NonTeachingMeritStrategy;
use App\Domain\Merit\StatutoryViolation;
use App\Domain\Merit\TeachingMeritStrategy;
use App\Domain\Shortlist\ShortlistFormula;

/**
 * The two merit regimes are irreconcilable, and the schema has to express
 * both. A screening score leaking into a teaching merit list is a statutory
 * violation, not a bug.
 */
it('ranks a teaching merit list on the interview alone', function (): void {
    $ranked = (new TeachingMeritStrategy)->rank([
        ['application_id' => 1, 'interview_score' => 72],
        ['application_id' => 2, 'interview_score' => 81],
        ['application_id' => 3, 'interview_score' => 65],
    ]);

    expect(array_column($ranked, 'application_id'))->toBe([2, 1, 3]);
});

it('refuses a shortlisting score in a teaching merit list', function (): void {
    // UGC 2018 cl. 4.1 I Note: Tables 3A and 3B decide who is CALLED and take
    // no part in deciding who is SELECTED.
    expect(fn () => (new TeachingMeritStrategy)->rank([
        ['application_id' => 1, 'interview_score' => 72, 'shortlisting_score' => 68],
    ]))->toThrow(StatutoryViolation::class, 'must not enter a teaching merit list');
});

it('refuses a written-test score in a teaching merit list', function (): void {
    expect(fn () => (new TeachingMeritStrategy)->rank([
        ['application_id' => 1, 'interview_score' => 72, 'written_test_score' => 55],
    ]))->toThrow(StatutoryViolation::class, 'cl. 5.3');
});

it('throws rather than silently dropping the forbidden input', function (): void {
    // Silently dropping it would produce a list that looked right and was
    // computed wrongly, and nobody would find out until a candidate
    // challenged the appointment.
    try {
        (new TeachingMeritStrategy)->rank([['interview_score' => 10, 'shortlisting_score' => 90]]);
        $this->fail('The strategy should have refused.');
    } catch (StatutoryViolation $e) {
        expect($e)->toBeInstanceOf(LogicException::class);
    }
});

it('adds the papers and weights the interview for non-teaching', function (): void {
    // CRR Rule 11 III(f)–(g): the exact opposite of the teaching regime.
    $ranked = (new NonTeachingMeritStrategy)->rank([
        ['application_id' => 1, 'group' => 'A', 'paper_one_score' => 60, 'paper_two_score' => 55, 'interview_score' => 15],
        ['application_id' => 2, 'group' => 'A', 'paper_one_score' => 58, 'paper_two_score' => 60, 'interview_score' => 18],
    ]);

    expect($ranked[0]['application_id'])->toBe(2)
        ->and($ranked[0]['total'])->toBe(121.6)      // 58 + 60 + 18 × 0.2
        ->and($ranked[1]['total'])->toBe(118.0);     // 60 + 55 + 15 × 0.2
});

it('refuses an interview score for a Group B or C post while the conflict stands', function (): void {
    // Rule 11 III(g) weights an interview into the Group B/C merit list;
    // Rule 22.8 forbids any interview for those groups. The source does not
    // reconcile them, and OQ-008 is with the legal cell.
    expect(fn () => (new NonTeachingMeritStrategy)->rank([
        ['group' => 'B', 'paper_one_score' => 60, 'interview_score' => 15],
    ]))->toThrow(StatutoryViolation::class, 'OQ-008');
});

it('ranks a Group C list on the papers alone', function (): void {
    $ranked = (new NonTeachingMeritStrategy)->rank([
        ['application_id' => 1, 'group' => 'C', 'paper_one_score' => 60, 'paper_two_score' => 50],
        ['application_id' => 2, 'group' => 'C', 'paper_one_score' => 70, 'paper_two_score' => 45],
    ]);

    expect($ranked[0]['application_id'])->toBe(2);
});

// DR-019 — the shortlisting ratio.

it('calls five for one post and three for each after', function (): void {
    $formula = new ShortlistFormula;

    expect($formula->for(1))->toBe(5)
        ->and($formula->for(2))->toBe(8)
        ->and($formula->for(3))->toBe(11)       // 5 + 3 × 2
        ->and($formula->for(10))->toBe(32);
});

it('refuses to shortlist for a post with no vacancies', function (): void {
    expect(fn () => (new ShortlistFormula)->for(0))
        ->toThrow(InvalidArgumentException::class, 'no vacancies');
});

it('is configurable within a ceiling', function (): void {
    // The ratio is the university's to set (Table 3A Note B), but a formula
    // that called everybody would make shortlisting meaningless -- and a
    // committee that must interview forty people for one post on the day of
    // the meeting cannot do it properly.
    $generous = new ShortlistFormula(base: 10, increment: 5);

    expect($generous->for(2))->toBe(15);

    expect(fn () => $generous->assertWithinCeiling(vacancies: 1, called: 30))
        ->toThrow(InvalidArgumentException::class, 'exceeds the ceiling');

    $generous->assertWithinCeiling(vacancies: 10, called: 45);
    expect(true)->toBeTrue();
});
