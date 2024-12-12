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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('widget_id');
            $table->string('user_note')->nullable();
            // $table->json('value')->nullable();
            $table->enum('type', ['text', 'textarea', 'file', 'color', 'input', 'select_option'])->default('text');// file, text, textarea, color
            // $table->integer('order')->default(0);
            $table->timestamps();

            $table->foreign('widget_id')->references('id')->on('widgets')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
