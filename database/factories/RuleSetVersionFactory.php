<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\RuleSet;
use App\Models\RuleSetVersion;
use App\Support\Canonical\CanonicalJson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RuleSetVersion>
 */
class RuleSetVersionFactory extends Factory
{
    protected $model = RuleSetVersion::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $payload = $this->ugc2018Payload();

        return [
            'rule_set_id' => RuleSet::query()->firstOrCreate(
                ['slug' => 'ugc-teaching-2018'],
                ['title' => 'UGC Regulations 2018', 'applies_to' => ['teaching']],
            )->getKey(),
            'version' => '2018.1',
            'status' => 'active',
            'payload' => $payload,
            'content_hash' => CanonicalJson::hash($payload),
        ];
    }

    /**
     * The values transcribed from the Gazette, with their clause references.
     *
     * The two that matter most here are the ones the previous work got wrong:
     * PI and Co-PI at 50 per cent EACH, and six impact-factor bands beginning
     * with "less than 1", which its file omitted so every band shifted down.
     *
     * @return array<string, mixed>
     */
    private function ugc2018Payload(): array
    {
        return [
            'strategy' => 'weighted_points',
            'evidence' => [
                'required' => ['citation' => 'UGC 2018 Appendix II Table 2, header'],
            ],
            'categories' => [
                'journal_paper' => [
                    'citation' => 'UGC 2018 Appendix II Table 2, category 1',
                    // Column I 8, Column II 10. A flat 8 understates every
                    // Column II candidate by a fifth.
                    'points' => ['column_I' => 8, 'column_II' => 10],
                ],
                'book' => [
                    'citation' => 'UGC 2018 Appendix II Table 2, category 2',
                    'points' => ['column_I' => 10, 'column_II' => 12],
                ],
                'project_completed' => [
                    'citation' => 'UGC 2018 Appendix II Table 2, category 5(a)',
                    'points' => ['column_I' => 10, 'column_II' => 10],
                ],
            ],
            'impact_factor' => [
                'citation' => 'UGC 2018 Appendix II Table 2, impact factor note',
                'mode' => 'add',
                // Six bands. The previous file had five and shifted them all.
                'bands' => [
                    ['min' => null, 'max' => 1, 'points' => 5],
                    ['min' => 1, 'max' => 2, 'points' => 10],
                    ['min' => 2, 'max' => 5, 'points' => 15],
                    ['min' => 5, 'max' => 10, 'points' => 20],
                    ['min' => 10, 'max' => null, 'points' => 25],
                ],
            ],
            'apportionment' => [
                'two_authors' => ['factor' => 0.7, 'citation' => 'UGC 2018 Appendix II Table 2, joint authorship'],
                'many_authors' => [
                    'lead_factor' => 0.7,
                    'joint_factor' => 0.3,
                    'citation' => 'UGC 2018 Appendix II Table 2, joint authorship',
                ],
                // 50 per cent EACH — the Gazette, the FN-1 form and the AMU
                // Ordinances all agree, against the previous file's 100/50.
                'joint_project' => ['factor' => 0.5, 'citation' => 'UGC 2018 Appendix II Table 2, joint projects'],
            ],
            'caps' => [
                'combined' => [
                    'categories' => ['ict_pedagogy', 'invited_lecture'],
                    'ratio' => 0.3,
                    'citation' => 'UGC 2018 Appendix II Table 2, cap on categories 5(b) and 6',
                ],
            ],
            'floors' => [
                'category_minimum' => [
                    'count' => 3,
                    'citation' => 'UGC 2018 Appendix II Table 2, minimum of three categories',
                ],
            ],
        ];
    }

    /**
     * A version carrying the six unratified Table 2 ambiguities (DR-013).
     */
    public function pendingRatification(): static
    {
        return $this->state(function (array $attributes): array {
            $payload = $attributes['payload'];
            $payload['impact_factor']['pending_ratification'] = true;
            $payload['caps']['combined']['pending_ratification'] = true;
            $payload['floors']['category_minimum']['pending_ratification'] = true;

            return ['payload' => $payload, 'content_hash' => CanonicalJson::hash($payload)];
        });
    }

    public function draft2025(): static
    {
        return $this->state(function (): array {
            $payload = [
                'strategy' => 'threshold_count',
                'areas' => [
                    'minimum' => 4,
                    'list' => ['teaching', 'research', 'outreach'],
                    'teaching' => ['citation' => 'UGC draft 2025, notable contributions'],
                    'research' => ['citation' => 'UGC draft 2025, notable contributions'],
                    'outreach' => ['citation' => 'UGC draft 2025, notable contributions'],
                ],
            ];

            return [
                'version' => '2025.draft',
                // Authored and INACTIVE (DR-006). It loads without a code
                // change if the draft is ever notified.
                'status' => 'draft',
                'payload' => $payload,
                'content_hash' => CanonicalJson::hash($payload),
            ];
        });
    }

    public function nonTeaching(): static
    {
        return $this->state(function (): array {
            $payload = [
                'strategy' => 'non_teaching_test',
                'components' => [
                    'paper_one' => ['weight' => 1.0, 'citation' => 'UGC CRR 2022 Rule 11 III(f)'],
                    'paper_two' => ['weight' => 1.0, 'citation' => 'UGC CRR 2022 Rule 11 III(f)'],
                    // 20 per cent of the total, additive — the opposite of the
                    // teaching regime, where a screening score must never
                    // enter the merit list.
                    'interview' => ['weight' => 0.2, 'citation' => 'UGC CRR 2022 Rule 11 III(g)'],
                    'skill_test' => ['minimum' => 25, 'citation' => 'UGC CRR 2022 Rule 11 III(f)'],
                ],
            ];

            return [
                'version' => '2022.1',
                'payload' => $payload,
                'content_hash' => CanonicalJson::hash($payload),
            ];
        });
    }
}
