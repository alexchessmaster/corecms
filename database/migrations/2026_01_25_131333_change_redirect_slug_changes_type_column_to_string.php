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
        Schema::table('redirect_slug_changes', function (Blueprint $table) {
            $table->string('type')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('redirect_slug_changes', function (Blueprint $table) {
            $table->enum('type', [
                'manual', 
                'category_created', 
                'category_updated', 
                'category_deleted', 
                'article_created', 
                'article_updated', 
                'article_deleted',
                'book_genre_created',
                'book_genre_updated', 
                'book_genre_deleted',
                'book_created',
                'book_updated',
                'book_deleted'
            ])->change();
        });
    }
};
