<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1, 2, 3 in TemplateSeeder
        // 4
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'Front Page',
            'da' => 'Forside',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('/'),
            'da' => '/' . Str::slug('/'),
        ]);
        $page->save();

        // 5
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'Web development',
            'da' => 'Webudvikling',
        ]);
        $page->setTranslations('slug', [
            'en' => '/services/' . Str::slug('Web development'),
            'da' => '/services/' . Str::slug('Webudvikling'),
        ]);
        $page->save();

        // 6
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'Contact',
            'da' => 'Kontact',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('Contact'),
            'da' => '/' . Str::slug('Kontact'),
        ]);
        $page->save();

        // 7
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'Articles',
            'da' => 'Artikler',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('Articles'),
            'da' => '/' . Str::slug('Artikler'),
        ]);
        $page->save();
    }
}
