<?php

namespace App\Modules\Books\Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class BookControllerTest extends TestCase
{
    // use RefreshDatabase;

    public function test_that_admin_can_see_books()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(200);
    }

    public function test_that_editor_can_see_books()
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(200);
    }

    public function test_author_can_see_books()
    {
        $user = User::factory()->create();
        $user->assignRole('author');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(200);
    }

    public function test_viewer_cannot_see_books()
    {
        $response = $this->get('/admin/books');
        $response->assertStatus(302);

        $user = User::factory()->create();
        $user->assignRole('guest');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(403);
    }
}
