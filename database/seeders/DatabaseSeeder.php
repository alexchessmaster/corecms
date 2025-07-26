<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Page;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\TranslationTextSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(UserSeeder::class);
        $this->call(SettingSeeder::class);
        $this->call(MenuSeeder::class);

        $this->call(WidgetSeeder::class);
        $this->call(FieldSeeder::class);
        $this->call(FieldWidgetSeeder::class);
        
        $this->call(PageSeeder::class);
        $this->call(WidgetableSeeder::class);
        
        $this->call(WidgetFieldValuesSeeder::class);
        
        $this->call(CategorySeeder::class);
        $this->call(TagSeeder::class);
        $this->call(ArticleSeeder::class);
        $this->call(TranslationTextSeeder::class);
    }
}
