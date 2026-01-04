<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeArticles()
    {
        $user = User::factory()->create();
        $user->assignRole('admin');
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(200);
    }

    public function testEditorCanSeeArticles()
    {
        $user = User::factory()->create();
        $user->assignRole('editor');
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(200);
    }

    public function testAuthorCanSeeArticles()
    {
        $user = User::factory()->create();
        $user->assignRole('author');
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(200);
    }

    public function testViewerCannotSeeArticles()
    {
        $user = User::factory()->create();
        $user->assignRole('guest');
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(403);
    }
}