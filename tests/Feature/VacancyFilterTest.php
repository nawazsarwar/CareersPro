<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VacancyFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_vacancy_listing_displays_posts()
    {
        $user = User::factory()->create();

        $post = Post::factory()->create(['title' => 'Assistant Professor in Computer Science']);

        $response = $this->withoutMiddleware()->actingAs($user)->get('/posts');

        $response->assertSee('Assistant Professor in Computer Science');
    }
}
