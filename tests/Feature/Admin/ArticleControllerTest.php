<?php

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testAdminCanSeeArticles()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(200);
    }

    public function testEditorCanSeeArticles()
    {
        $user = User::factory()->create(['role' => 'editor']);
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(200);
    }

    public function testAuthorCannotSeeArticles()
    {
        $user = User::factory()->create(['role' => 'author']);
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(403);
    }

    public function testViewerCannotSeeArticles()
    {
        $user = User::factory()->create(['role' => 'viewer']);
        $this->actingAs($user);
        $response = $this->get('/admin/articles');
        $response->assertStatus(403);
    }
}