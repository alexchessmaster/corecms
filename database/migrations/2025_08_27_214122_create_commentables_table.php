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
        Schema::create('commentables', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable');
            $table->json('content');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->smallInteger('stars')->default(0);
            $table->enum('status', [
                'pending',
                'approved',
                'rejected',
                'spam',
                'hidden',
                'deleted'
            ])->default('pending');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commentables');
    }
};
