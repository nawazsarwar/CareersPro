<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Gate;

class AdminPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_posts_index_with_tailwind()
    {
        $user = User::factory()->create();

        // Mock the Gate to allow 'post_access' for this specific test
        Gate::define('post_access', function () {
            return true;
        });
        Gate::define('post_create', function () {
            return true;
        });
        Gate::define('post_delete', function () {
            return true;
        });
        Gate::define('post_edit', function () {
            return true;
        });
        Gate::define('post_show', function () {
            return true;
        });

        Post::factory()->create(['title' => 'Test Admin Post']);

        $response = $this->withoutMiddleware()->actingAs($user)->get(route('admin.posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Admin Post');
        $response->assertDontSee('dataTables');
    }
}
