<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        $title = $this->faker->jobTitle;
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'vacancies' => 1,
            'location' => 'Main Campus',
            'pay_level' => '10',
            'pay_range' => '57700-182400',
            'fee' => 500,
            'withdrawn' => 0,
            'status' => 1,
        ];
    }
}
