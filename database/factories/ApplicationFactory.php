<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\LifecycleState;
use App\Models\Application;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Application>
 */
class ApplicationFactory extends Factory
{
    protected $model = Application::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $post = Post::factory()->create();

        return [
            'application_no' => (string) fake()->unique()->numberBetween(10000000, 99999999),
            'user_id' => User::factory()->candidate(),
            'post_id' => $post->getKey(),
            'advertisement_id' => $post->advertisement_id,
            'lifecycle_state' => LifecycleState::Draft,
        ];
    }
}
