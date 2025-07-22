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
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('page_id')->constrained();
            $table->string('name');
            $table->string('key');
            $table->string('user_note')->nullable();
            $table->string('image')->nullable();
            // $table->enum('type', ['page', 'template'])->default('page');
            $table->boolean('active')->default(true);
            $table->boolean('locked_fields_value')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('widgets');
    }
};
