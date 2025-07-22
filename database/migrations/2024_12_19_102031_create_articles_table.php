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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->json('slug');
            $table->json('title');
            $table->json('description')->nullable();
            $table->json('image')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreignId('template_id')->nullable()->constrained('templates')->nullOnDelete();
            $table->string('primary_language', 2)->nullable();

            $table->boolean('sitemap_exclude')->nullable();
            $table->float('sitemap_priority', 1)->nullable();
            $table->enum('sitemap_change_frequency', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly'])->nullable();

            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
