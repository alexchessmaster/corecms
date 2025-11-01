<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only run the seeder if roles and permissions tables are empty
        if (\DB::table('roles')->count() === 0 && \DB::table('permissions')->count() === 0) {
            Artisan::call('db:seed', [
                '--class' => 'Database\\Seeders\\RoleAndPermissionSeeder',
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback for seeder data
    }
};
