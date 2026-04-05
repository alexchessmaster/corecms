<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Fix articles table — drop json author column, add author_id FK
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('author');
            $table->unsignedBigInteger('author_id')->nullable()->after('category_id');
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
        });

        // 2. Fix products table — re-point author_id FK to authors
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
        });

        // 3. Fix news table — re-point author_id FK to authors
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->foreign('author_id')->references('id')->on('authors')->onDelete('set null');
        });

        // 4. Drop the now-obsolete author tables
        Schema::dropIfExists('product_authors');
        Schema::dropIfExists('news_authors');
    }

    public function down(): void
    {
        // Re-create product_authors
        Schema::create('product_authors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // Re-create news_authors
        Schema::create('news_authors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // Revert news FK back to news_authors
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->foreign('author_id')->references('id')->on('news_authors')->onDelete('set null');
        });

        // Revert products FK back to product_authors
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->foreign('author_id')->references('id')->on('product_authors')->onDelete('set null');
        });

        // Revert articles — drop author_id, restore json author column
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign(['author_id']);
            $table->dropColumn('author_id');
            $table->json('author')->nullable();
        });
    }
};