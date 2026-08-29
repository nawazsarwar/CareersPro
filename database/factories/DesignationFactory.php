<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Cadre;
use App\Enums\SelectionMethod;
use App\Models\Designation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Designation>
 */
class DesignationFactory extends Factory
{
    protected $model = Designation::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => strtoupper(fake()->unique()->bothify('DESIG-####')),
            'name' => fake()->jobTitle(),
            'cadre' => Cadre::Teaching,
            'group' => null,
            'pay_level' => 'A10',
            'selection_method' => SelectionMethod::InterviewOnly,
            'status' => 'active',
        ];
    }

    public function nonTeaching(string $group = 'B'): static
    {
        return $this->state(fn (): array => [
            'cadre' => Cadre::NonTeaching,
            'group' => $group,
            'pay_level' => 'L7',
            'selection_method' => SelectionMethod::WrittenSkillInterview,
        ]);
    }
}
