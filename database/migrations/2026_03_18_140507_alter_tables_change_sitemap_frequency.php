<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['news', 'products', 'pages', 'articles', 'books'];

        foreach ($tables as $table) {
            DB::statement("
                ALTER TABLE `$table` 
                MODIFY `sitemap_change_frequency` 
                ENUM('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never') 
                NULL
            ");
        }
    }

    public function down(): void
    {
        $tables = ['news', 'products', 'pages', 'articles', 'books'];

        foreach ($tables as $table) {
            DB::statement("
                ALTER TABLE `$table` 
                MODIFY `sitemap_change_frequency` 
                ENUM('always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly') 
                NULL
            ");
        }
    }
};
