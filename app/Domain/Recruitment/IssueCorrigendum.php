<?php

declare(strict_types=1);

namespace App\Domain\Recruitment;

use App\Models\Advertisement;
use App\Models\Corrigendum;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * The only way a published advertisement changes.
 *
 * The `changes` payload records what moved and from what, so a candidate
 * reading a corrigendum can see the difference rather than being told to
 * compare two documents themselves.
 */
final class IssueCorrigendum
{
    /**
     * The fields a corrigendum may alter. Deliberately short: a corrigendum
     * that could rewrite eligibility would be an edit wearing a different
     * name, and the candidates who applied under the original terms would have
     * a grievance the system could not answer.
     *
     * @var list<string>
     */
    private const AMENDABLE = [
        'default_closing_date',
        'default_payment_closing_date',
        'description',
    ];

    public function __construct(private readonly NextWorkingDay $workingDay) {}

    /**
     * @param  array<string, mixed>  $changes
     * @param  list<string>  $holidays
     */
    public function handle(
        Advertisement $advertisement,
        User $actor,
        string $description,
        array $changes = [],
        array $holidays = [],
    ): Corrigendum {
        if (! $advertisement->isPublished()) {
            throw new RuntimeException('A corrigendum applies to a published advertisement only.');
        }

        $unknown = array_diff(array_keys($changes), self::AMENDABLE);

        if ($unknown !== []) {
            throw new RuntimeException(sprintf(
                'A corrigendum cannot change [%s]. Amendable fields are [%s].',
                implode(', ', $unknown),
                implode(', ', self::AMENDABLE),
            ));
        }

        return DB::transaction(function () use ($advertisement, $actor, $description, $changes, $holidays): Corrigendum {
            $before = [];

            foreach ($changes as $field => $value) {
                $before[$field] = $advertisement->getAttribute($field);

                if ($field === 'default_closing_date' && $value !== null) {
                    $value = $this->workingDay->from(CarbonImmutable::parse((string) $value), $holidays);
                }

                $advertisement->forceFill([$field => $value]);
            }

            $advertisement->save();

            $next = (int) $advertisement->corrigenda()->max('corrigendum_no') + 1;

            return $advertisement->corrigenda()->create([
                'corrigendum_no' => $next,
                'issued_on' => CarbonImmutable::now()->toDateString(),
                'description' => $description,
                'changes' => ['from' => $before, 'to' => $changes],
                'published_at' => CarbonImmutable::now(),
                'issued_by_id' => $actor->getKey(),
            ]);
        });
    }
}
