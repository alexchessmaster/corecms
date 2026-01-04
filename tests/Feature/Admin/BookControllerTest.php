<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeBooks()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(200);
    }

    public function testEditorCanSeeBooks()
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(200);
    }

    public function testAuthorCanSeeBooks()
    {
        $user = User::factory()->create();
        $user->assignRole('author');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(200);
    }

    public function testViewerCannotSeeBooks()
    {
        $user = User::factory()->create();
        $user->assignRole('guest');
        $this->actingAs($user);
        $response = $this->get('/admin/books');
        $response->assertStatus(403);
    }
}