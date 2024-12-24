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
            $table->string('key')->nullable();
            // $table->json('value')->nullable();
            $table->enum('type', ['input', 'textarea_one_line', 'textarea_small', 'textarea_large', 'file', 'color', 'code', 'select_option_left_center_right', 'select_option_on_off'])->default('input');// file, text, textarea, color
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
