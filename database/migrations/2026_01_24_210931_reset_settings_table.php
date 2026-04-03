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
        // empty the table since I didn't modify the settings much
        \DB::table('settings')->truncate();

        // Call the SettingSeeder using Artisan
        \Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\SettingSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

    }
};
