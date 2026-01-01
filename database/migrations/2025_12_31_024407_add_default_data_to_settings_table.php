<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create guest role if it doesn't exist
        if (!Role::where('name', 'guest')->exists()) {
            Role::create([
                'name' => 'guest',
                'guard_name' => 'web'
            ]);
        }

        // Insert or update settings
        $settings = [
            [
                'key' => 'article-prefix',
                'value' => 'articles',
                'description' => 'Prefix for article URLs (e.g., /articles/article-path). Leave empty for no prefix.'
            ],
            [
                'key' => 'book-prefix',
                'value' => 'books',
                'description' => 'Prefix for book URLs (e.g., /books/book-path). Leave empty for no prefix.'
            ],
            [
                'key' => 'product-prefix',
                'value' => 'products',
                'description' => 'Prefix for product URLs (e.g., /products/product-path). Leave empty for no prefix.'
            ],
            [
                'key' => 'default-user-role',
                'value' => 'guest',
                'description' => 'Role automatically assigned to new users after registration.'
            ],
            [
                'key' => 'article-path-hierarchical',
                'value' => 'true',
                'description' => 'If true, article URLs include category hierarchy (e.g., /parent-category/category/article-path). If false, URLs are flat (e.g., /article-path).'
            ],
            [
                'key' => 'book-path-hierarchical',
                'value' => 'true',
                'description' => 'If true, book URLs include category hierarchy (e.g., /parent-category/category/book-path). If false, URLs are flat (e.g., /book-path).'
            ],
            [
                'key' => 'product-path-hierarchical',
                'value' => 'true',
                'description' => 'If true, product URLs include category hierarchy (e.g., /parent-category/category/product-path). If false, URLs are flat (e.g., /product-path).'
            ],
            
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'description' => $setting['description']
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
