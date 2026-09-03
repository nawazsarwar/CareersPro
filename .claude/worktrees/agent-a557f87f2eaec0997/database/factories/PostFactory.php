<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AppointmentNature;
use App\Enums\SelectionMethod;
use App\Models\Advertisement;
use App\Models\Post;
use App\Models\PostType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->jobTitle();

        return [
            'advertisement_id' => Advertisement::factory(),
            'post_type_id' => PostType::query()->firstOrCreate(
                ['code' => 'TEACH-GEN'],
                [
                    'name' => 'Teaching (General)',
                    'default_selection_method' => SelectionMethod::InterviewOnly,
                    'has_scrutiny_gate' => true,
                    'has_written_test_gate' => false,
                    'has_interview_gate' => true,
                ],
            )->getKey(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 99999),
            'appointment_nature' => AppointmentNature::General,
            'vacancies' => fake()->numberBetween(1, 5),
            'fee' => 1000,
            'age_limit' => 40,
            'closing_date' => now()->addDays(45)->toDateString(),
            'status' => 'draft',
        ];
    }
}
