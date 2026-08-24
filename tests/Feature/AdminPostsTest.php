<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPostsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_posts_index_with_tailwind()
    {
        $user = User::factory()->create();

        $role = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'post_access']);
        $role->permissions()->sync([$permission->id]);
        $user->roles()->sync([$role->id]);

        Post::factory()->create(['title' => 'Test Admin Post']);

        $response = $this->withoutMiddleware()->actingAs($user)->get(route('admin.posts.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Admin Post');
        $response->assertDontSee('dataTables');
    }
}
