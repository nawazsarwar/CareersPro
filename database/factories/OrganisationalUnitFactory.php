<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OrganisationalUnit;
use App\Models\OrganisationalUnitType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganisationalUnit>
 */
class OrganisationalUnitFactory extends Factory
{
    protected $model = OrganisationalUnit::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->unique()->words(3, true),
            'code' => strtoupper(fake()->unique()->bothify('OU-####')),
            'status' => 'published',
            'type_id' => OrganisationalUnitType::factory(),
            'parent_id' => null,
        ];
    }

    public function childOf(OrganisationalUnit $parent): static
    {
        return $this->state(fn (): array => ['parent_id' => $parent->getKey()]);
    }
}
