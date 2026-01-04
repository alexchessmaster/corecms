<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeCategories()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $response = $this->get('/admin/categories');
        $response->assertStatus(200);
    }

    public function testEditorCanSeeCategories()
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $this->actingAs($user);
        $response = $this->get('/admin/categories');
        $response->assertStatus(200);
    }

    public function testAuthorCannotSeeCategories()
    {
        $user = User::factory()->create();
        $user->assignRole('author');
        $this->actingAs($user);
        $response = $this->get('/admin/categories');
        $response->assertStatus(403);
    }

    public function testViewerCannotSeeCategories()
    {
        $user = User::factory()->create();
        $user->assignRole('guest');
        $this->actingAs($user);
        $response = $this->get('/admin/categories');
        $response->assertStatus(403);
    }
}