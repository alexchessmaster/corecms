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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->json('slug');//->index();
            $table->json('title');
            $table->enum('type', ['page', 'template'])->default('page');
            $table->boolean('sitemap_exclude')->nullable();
            $table->float('sitemap_priority', 1)->nullable();
            $table->enum('sitemap_change_frequently', ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
