<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OrganisationalUnitType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganisationalUnitType>
 */
class OrganisationalUnitTypeFactory extends Factory
{
    protected $model = OrganisationalUnitType::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->word(),
            'code' => strtoupper(fake()->unique()->bothify('TYPE-####')),
            'category' => 'academic',
            'is_recruitment_eligible' => true,
        ];
    }
}
