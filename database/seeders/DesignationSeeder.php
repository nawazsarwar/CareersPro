<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\Cadre;
use App\Enums\SelectionMethod;
use App\Models\Designation;
use Illuminate\Database\Seeder;

/**
 * The eleven UGC teaching cadres and the principal non-teaching ones.
 *
 * Seeded from the regulatory spine in docs/v3/01-design/regulatory/, not from
 * the Data Lake's 346 designation names -- that table has `code`, `pay_grade`,
 * `retirement_age` and `type_id` NULL on every one of its rows and its
 * `designation_types` table is empty, so it is a vocabulary cross-check and
 * nothing more.
 *
 * The full Schedule-1 import -- 358 pages, keyed organisational unit by
 * organisational unit, including the JNMC Hospital, Nursing, Trauma Centre and
 * Dental College cadres the UGC model rules omit -- is a bulk import task with
 * a manual review step, not a seeder.
 */
class DesignationSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->teaching() as $row) {
            $this->create($row, Cadre::Teaching, SelectionMethod::InterviewOnly);
        }

        foreach ($this->library() as $row) {
            $this->create($row, Cadre::Library, SelectionMethod::InterviewOnly);
        }

        foreach ($this->physicalEducation() as $row) {
            $this->create($row, Cadre::PhysicalEducation, SelectionMethod::InterviewOnly);
        }

        foreach ($this->nonTeaching() as $row) {
            $this->create($row, Cadre::NonTeaching, SelectionMethod::from($row['method']), $row['group']);
        }
    }

    /**
     * @param  array<string, mixed>  $row
     */
    private function create(array $row, Cadre $cadre, SelectionMethod $method, ?string $group = null): void
    {
        Designation::query()->updateOrCreate(
            ['code' => $row['code']],
            [
                'name' => $row['name'],
                'cadre' => $cadre,
                'group' => $group,
                'pay_level' => $row['pay_level'],
                'retirement_age' => $row['retirement_age'] ?? null,
                'min_age' => $row['min_age'] ?? null,
                'max_age' => $row['max_age'] ?? null,
                'selection_method' => $method,
                'essential_qualification' => $row['essential'] ?? null,
                'status' => 'active',
            ],
        );
    }

    /**
     * UGC Regulations 2018. Research-score thresholds are recorded here as the
     * qualification they are; the engine reads them from the versioned
     * ruleset, never from this table.
     *
     * @return list<array<string, mixed>>
     */
    private function teaching(): array
    {
        return [
            ['code' => 'ASST-PROF', 'name' => 'Assistant Professor', 'pay_level' => 'A10', 'retirement_age' => 65,
                'essential' => ['phd_mandatory' => false, 'net_required_unless_exempt' => true, 'citation' => 'UGC 2018 cl. 4.1 I']],
            ['code' => 'ASSOC-PROF', 'name' => 'Associate Professor', 'pay_level' => 'A13A', 'retirement_age' => 65,
                'essential' => ['phd_mandatory' => true, 'experience_years' => 8, 'publications' => 7, 'research_score' => 75, 'citation' => 'UGC 2018 cl. 4.1 II']],
            ['code' => 'PROF', 'name' => 'Professor', 'pay_level' => 'A14', 'retirement_age' => 65,
                'essential' => ['phd_mandatory' => true, 'experience_years' => 10, 'publications' => 10, 'research_score' => 120, 'guided_doctoral_candidate' => true, 'citation' => 'UGC 2018 cl. 4.1 III']],
            ['code' => 'SR-PROF', 'name' => 'Senior Professor', 'pay_level' => 'A15', 'retirement_age' => 65,
                'essential' => ['experience_as_professor_years' => 10, 'best_publications' => 10, 'phds_awarded_in_10_years' => 2, 'expert_review' => 3, 'citation' => 'UGC 2018 cl. 4.1 IV']],
            ['code' => 'PRINCIPAL', 'name' => 'College Principal (and Professor)', 'pay_level' => 'A14', 'retirement_age' => 65,
                'essential' => ['phd_mandatory' => true, 'experience_years' => 15, 'publications' => 10, 'research_score' => 110, 'citation' => 'UGC 2018 cl. 4.2']],
        ];
    }

    /**
     * DR-014: Librarian and DPES cadres score in Column II at 10 points a
     * paper, not Column I at 8.
     *
     * @return list<array<string, mixed>>
     */
    private function library(): array
    {
        return [
            ['code' => 'ASST-LIB', 'name' => 'Assistant Librarian', 'pay_level' => 'A10', 'retirement_age' => 62,
                'essential' => ['phd_mandatory' => false, 'net_required_unless_exempt' => true]],
            ['code' => 'DY-LIB', 'name' => 'Deputy Librarian', 'pay_level' => 'A12', 'retirement_age' => 62,
                'essential' => ['phd_mandatory' => true, 'experience_years' => 8]],
            ['code' => 'UNIV-LIB', 'name' => 'University Librarian', 'pay_level' => 'A14', 'retirement_age' => 62,
                'essential' => ['phd_mandatory' => true, 'experience_years' => 10]],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function physicalEducation(): array
    {
        return [
            ['code' => 'ASST-DPES', 'name' => 'Assistant Director of Physical Education and Sports', 'pay_level' => 'A10', 'retirement_age' => 62,
                'essential' => ['phd_mandatory' => false, 'net_required_unless_exempt' => true]],
            ['code' => 'DY-DPES', 'name' => 'Deputy Director of Physical Education and Sports', 'pay_level' => 'A12', 'retirement_age' => 62,
                'essential' => ['phd_mandatory' => true, 'experience_years' => 8]],
            ['code' => 'DIR-PES', 'name' => 'Director of Physical Education and Sports', 'pay_level' => 'A14', 'retirement_age' => 62,
                'essential' => ['phd_mandatory' => true, 'experience_years' => 10]],
        ];
    }

    /**
     * UGC Model CRR 2022, cross-checked against AMU's own rules; where they
     * differ, AMU's govern.
     *
     * @return list<array<string, mixed>>
     */
    private function nonTeaching(): array
    {
        return [
            ['code' => 'REGISTRAR', 'name' => 'Registrar', 'group' => 'A', 'pay_level' => 'L14', 'max_age' => 57, 'method' => 'interview_only'],
            ['code' => 'FIN-OFFICER', 'name' => 'Finance Officer', 'group' => 'A', 'pay_level' => 'L14', 'max_age' => 57, 'method' => 'interview_only'],
            ['code' => 'COE', 'name' => 'Controller of Examinations', 'group' => 'A', 'pay_level' => 'L14', 'max_age' => 57, 'method' => 'interview_only'],
            ['code' => 'DY-REGISTRAR', 'name' => 'Deputy Registrar', 'group' => 'A', 'pay_level' => 'L12', 'max_age' => 50, 'method' => 'interview_only'],
            ['code' => 'ASST-REGISTRAR', 'name' => 'Assistant Registrar', 'group' => 'A', 'pay_level' => 'L10', 'max_age' => 40, 'method' => 'written_interview'],
            ['code' => 'SEC-OFFICER', 'name' => 'Section Officer', 'group' => 'B', 'pay_level' => 'L7', 'max_age' => 35, 'method' => 'written_skill_interview'],
            ['code' => 'ASSISTANT', 'name' => 'Assistant', 'group' => 'B', 'pay_level' => 'L6', 'max_age' => 32, 'method' => 'written_skill_interview'],
            ['code' => 'UDC', 'name' => 'Upper Division Clerk', 'group' => 'C', 'pay_level' => 'L4', 'max_age' => 30, 'method' => 'written_skill_interview'],
            ['code' => 'LDC', 'name' => 'Lower Division Clerk', 'group' => 'C', 'pay_level' => 'L2', 'max_age' => 27, 'method' => 'written_skill_interview'],
            ['code' => 'MTS', 'name' => 'Multi-Tasking Staff', 'group' => 'C', 'pay_level' => 'L1', 'max_age' => 27, 'method' => 'trade_test'],
            ['code' => 'DRIVER', 'name' => 'Driver', 'group' => 'C', 'pay_level' => 'L2', 'max_age' => 27, 'method' => 'driving_test'],
            ['code' => 'LAB-ASSISTANT', 'name' => 'Laboratory Assistant', 'group' => 'C', 'pay_level' => 'L4', 'max_age' => 30, 'method' => 'written_skill_interview'],
        ];
    }
}
