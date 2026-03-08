<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('title');
            $table->json('slug');
            $table->unsignedBigInteger('views')->default(0);
            $table->json('description')->nullable();
            $table->json('image')->nullable();
            $table->unsignedBigInteger('news_category_id')->nullable();
            $table->unsignedBigInteger('author_id')->nullable();
            $table->dateTime('news_date')->nullable();

            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->dateTime('scheduled_at')->nullable();

            $table->boolean('sitemap_exclude')->nullable();
            $table->float('sitemap_priority', 1)->nullable();
            $table->enum('sitemap_change_frequency', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly'])->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('news_category_id')->references('id')->on('news_categories')->onDelete('set null');
            $table->foreign('author_id')->references('id')->on('news_authors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
