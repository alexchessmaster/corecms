<?php

namespace App\Modules\News\Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class NewsControllerTest extends TestCase
{
    // use RefreshDatabase;

    public function test_that_admin_can_see_news()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $response = $this->get('/admin/news');
        $response->assertStatus(200);
    }

    public function test_that_editor_can_see_news()
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $this->actingAs($user);
        $response = $this->get('/admin/news');
        $response->assertStatus(200);
    }

    public function test_author_can_see_news()
    {
        $user = User::factory()->create();
        $user->assignRole('author');
        $this->actingAs($user);
        $response = $this->get('/admin/news');
        $response->assertStatus(200);
    }

    public function test_viewer_cannot_see_news()
    {
        $response = $this->get('/admin/news');
        $response->assertStatus(403);
        
        $user = User::factory()->create();
        $user->assignRole('guest');
        $this->actingAs($user);
        $response = $this->get('/admin/news');
        $response->assertStatus(403);
    }

    
}
