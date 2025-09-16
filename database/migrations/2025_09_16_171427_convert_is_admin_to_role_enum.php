<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add the new role column
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'editor', 'author', 'viewer'])->default('author')->after('is_admin');
        });

        // Convert existing data: is_admin = true -> role = 'admin', is_admin = false -> role = 'author'
        DB::table('users')->where('is_admin', true)->update(['role' => 'admin']);
        DB::table('users')->where('is_admin', false)->update(['role' => 'author']);

        // Drop the old is_admin column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back the is_admin column
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('password');
        });

        // Convert role data back to is_admin
        DB::table('users')->where('role', 'admin')->update(['is_admin' => true]);
        DB::table('users')->whereIn('role', ['editor', 'author', 'viewer'])->update(['is_admin' => false]);

        // Drop the role column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
