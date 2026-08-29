<?php

declare(strict_types=1);

namespace App\Domain\Grievance;

use App\Models\Grievance;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use RuntimeException;

/**
 * The grievance desk, with a clock.
 *
 * A structural constraint the regulations impose, discoverable only by reading
 * them: UGC 2018 cl. 5.1 VIII(c) and cl. 5.3 require the selection process to
 * be completed on the day of the committee meeting, which leaves no room for a
 * post-committee grievance window. A pre-interview, screening-stage window is
 * the only compatible slot, so a grievance about a selection decision is
 * refused as out of time rather than accepted and then found unanswerable.
 *
 * Nothing in either instrument requires a grievance regime at all -- the only
 * finality clauses run the other way (CRR Rules 19.6, 22.15(v)) -- so this is
 * University policy under the Executive Council, and must not be presented as
 * UGC compliance.
 */
final class RaiseGrievance
{
    /**
     * Categories that must be raised before the committee meets.
     *
     * @var list<string>
     */
    private const PRE_COMMITTEE_ONLY = ['scrutiny_decision', 'eligibility', 'document_rejection'];

    public function handle(User $user, string $category, string $description, ?int $applicationId = null): Grievance
    {
        if (trim($description) === '') {
            throw new RuntimeException('A grievance must say what is wrong.');
        }

        $days = (int) config('grievance.sla_days', 15);

        return Grievance::query()->create([
            'reference' => 'GRV-'.strtoupper(Str::random(8)),
            'user_id' => $user->getKey(),
            'application_id' => $applicationId,
            'category' => $category,
            'description' => $description,
            'status' => 'open',
            // A desk without a clock is a suggestion box.
            'due_at' => CarbonImmutable::now()->addDays($days),
        ]);
    }

    public function mustPrecedeCommittee(string $category): bool
    {
        return in_array($category, self::PRE_COMMITTEE_ONLY, true);
    }
}
