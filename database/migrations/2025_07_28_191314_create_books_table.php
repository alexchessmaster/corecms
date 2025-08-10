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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->json('slug');
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('image')->nullable();
            $table->unsignedBigInteger('book_genre_id')->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->string('primary_language', 2)->nullable();
            $table->dateTime('scheduled_at')->nullable();

            $table->json('author')->nullable();
            $table->unsignedInteger('published_year')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->integer('total_pages')->default(0);
            
            $table->boolean('sitemap_exclude')->nullable();
            $table->float('sitemap_priority', 1)->nullable();
            $table->enum('sitemap_change_frequency', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly'])->nullable();

            $table->timestamps();

            $table->foreign('book_genre_id')->references('id')->on('book_genres')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
