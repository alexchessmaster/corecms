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
        Schema::create('redirect_slug_changes', function (Blueprint $table) {
            $table->id();
            $table->string('old_slug')->nullable();
            $table->string('new_slug')->nullable();
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
                'book_deleted',
                'product_category_created',
                'product_category_updated',
                'product_category_deleted',
                'product_tag_created',
                'product_tag_updated',
                'product_tag_deleted',
                'product_created',
                'product_updated',
                'product_deleted',
            ]);
            $table->string('language')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Assumes user is referenced by id
            $table->boolean('checked')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redirect_slug_changes');
    }
};
