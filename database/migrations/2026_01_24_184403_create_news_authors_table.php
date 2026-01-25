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
        Schema::create('news_authors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->json('name');
            $table->string('image_xs')->nullable(); // thumbnail
            $table->string('image_sm')->nullable(); // small mobile
            $table->string('image_md')->nullable(); // default
            $table->string('image')->nullable();    // original
            $table->json('biography')->nullable();
            $table->json('nationality')->nullable();
            $table->dateTime('date_of_birth')->nullable();
            $table->dateTime('date_of_death')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_authors');
    }
};
