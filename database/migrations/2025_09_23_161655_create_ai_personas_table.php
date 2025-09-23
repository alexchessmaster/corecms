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
        Schema::create('ai_personas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Helpful Assistant", "Code Reviewer", "Creative Writer"
            $table->string('description')->nullable(); // Brief description of the persona
            $table->text('system_prompt'); // The actual prompt template/instructions
            $table->string('suggested_model')->default('gpt-3.5-turbo'); // Recommended model for this persona
            $table->json('default_parameters')->nullable(); // Default temperature, max_tokens, etc.
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_public')->default(false); // Whether other users can use this persona
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_personas');
    }
};
