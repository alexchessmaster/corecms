<?php

namespace Database\Seeders;

use App\Modules\Books\Seeders\BookGenreSeeder;
use App\Modules\Books\Seeders\BookPolicySeeder;
use App\Modules\Products\seeders\ProductPolicySeeder;
use App\Modules\Users\seeders\AuthorPolicySeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call(LanguageSeeder::class);
        $this->call(RoleAndPermissionSeeder::class);
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

        $this->call(BookGenreSeeder::class);
        $this->call(BookPolicySeeder::class);

        $this->call(ProductPolicySeeder::class);

        $this->call(AuthorPolicySeeder::class);

        $this->call(TranslationTextSeeder::class);
    }
}
