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
        Schema::create('url_logs', function (Blueprint $table) {
            // $table->id(); // no need since we don't need to run query on this table often. and removing it can increase the write speed.
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('url');
            $table->string('params')->nullable();
            $table->string('ip_address', 45);
            $table->string('referrer')->nullable();
            $table->string('http_method', 10);
            $table->string('user_agent');
            $table->boolean('is_robot')->nullable();
            $table->timestamp('created_at')->useCurrent(); // we don't need updated_at since we don't update the table often
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('url_logs');
    }
};
