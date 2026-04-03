<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['articles', 'books', 'products', 'pages', 'news'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {

                if (!Schema::hasColumn($tableName, 'image')) {
                    $table->json('image')->nullable();
                }

                if (!Schema::hasColumn($tableName, 'image_medium')) {
                    $table->json('image_medium')->nullable();
                }

                if (!Schema::hasColumn($tableName, 'image_thumbnail')) {
                    $table->json('image_thumbnail')->nullable();
                }

            });
        }
    }

    public function down(): void
    {
        $tables = ['articles', 'books', 'products', 'pages', 'news'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {

                if (Schema::hasColumn($tableName, 'image')) {
                    $table->dropColumn('image');
                }

                if (Schema::hasColumn($tableName, 'image_medium')) {
                    $table->dropColumn('image_medium');
                }

                if (Schema::hasColumn($tableName, 'image_thumbnail')) {
                    $table->dropColumn('image_thumbnail');
                }

            });
        }
    }
};
