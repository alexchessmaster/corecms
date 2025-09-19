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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->json('name'); // Translatable field
            $table->json('slug'); // Translatable field
            $table->json('description')->nullable();
            $table->json('image')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('hide_from_frontend')->nullable();
            $table->enum('status', ['draft', 'published', 'scheduled'])->default('draft');
            $table->string('primary_language', 2)->nullable();

            $table->boolean('sitemap_exclude')->nullable();
            $table->float('sitemap_priority', 1)->nullable();
            $table->enum('sitemap_change_frequency', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly'])->nullable();

            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
