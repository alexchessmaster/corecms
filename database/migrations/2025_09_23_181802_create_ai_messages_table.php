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
        Schema::create('ai_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_chat_id')->constrained('ai_chats')->onDelete('cascade');
            $table->enum('role', ['user', 'assistant', 'system']); // Message sender type
            $table->text('content'); // The actual message content
            $table->integer('input_tokens')->nullable(); // Tokens for this specific message (for user messages)
            $table->integer('output_tokens')->nullable(); // Tokens for this specific message (for assistant responses)
            $table->decimal('message_cost_usd', 8, 6)->nullable(); // Cost for this specific message
            $table->json('metadata')->nullable(); // For storing additional message data (model params, etc.)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_messages');
    }
};
