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
        // Run the NewsPolicySeeder
        \Artisan::call('db:seed', [
            '--class' => 'App\\Modules\\News\\Seeders\\NewsPolicySeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optionally, you could remove the permissions/roles here if needed
    }
};
