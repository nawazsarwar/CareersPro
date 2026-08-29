<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AdvertisementStatus;
use App\Enums\AppointmentNature;
use App\Models\Advertisement;
use App\Models\AdvertisementType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Advertisement>
 */
class AdvertisementFactory extends Factory
{
    protected $model = Advertisement::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(4);

        return [
            'advertisement_no' => fake()->unique()->bothify('##/2026/NT'),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'advertisement_type_id' => AdvertisementType::query()->firstOrCreate(
                ['code' => 'GENERAL'],
                ['name' => 'General (permanent)'],
            )->getKey(),
            'appointment_nature' => AppointmentNature::General,
            'status' => AdvertisementStatus::Draft,
            'default_opening_date' => now()->toDateString(),
            // Comfortably beyond the thirty-day statutory minimum, so a test
            // that means to exercise the window has to set it deliberately.
            'default_closing_date' => now()->addDays(45)->toDateString(),
            'default_fee' => 1000,
        ];
    }

    public function published(): static
    {
        return $this->state(fn (): array => [
            'status' => AdvertisementStatus::Published,
            'published_at' => now(),
        ]);
    }

    public function local(): static
    {
        return $this->state(fn (): array => ['appointment_nature' => AppointmentNature::Local]);
    }
}
