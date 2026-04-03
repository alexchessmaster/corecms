<?php

use App\Modules\Settings\Models\Setting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create guest role if it doesn't exist
        if (!Role::where('name', 'guest')->exists()) {
            Role::create([
                'name' => 'guest',
                'guard_name' => 'web'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
