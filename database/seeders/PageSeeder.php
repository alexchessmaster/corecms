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
            'en' => '/' . Str::slug('Web development'),
            'da' => '/' . Str::slug('Webudvikling'),
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

        // 8
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'App development',
            'da' => 'App udvikling',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('App development'),
            'da' => '/' . Str::slug('App udvikling'),
        ]);
        $page->save();

        // 9
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'Hosting',
            'da' => 'Hosting',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('Hosting'),
            'da' => '/' . Str::slug('Hosting'),
        ]);
        $page->save();

        // 10
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'IT consultancy',
            'da' => 'IT rådgivning',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('IT consultancy'),
            'da' => '/' . Str::slug('IT rådgivning'),
        ]);
        $page->save();

        // 11
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'Secure VPN',
            'da' => 'Sikker VPN',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('Secure VPN'),
            'da' => '/' . Str::slug('Sikker VPN'),
        ]);
        $page->save();

        // 12
        $page = new Page;
        $page->setTranslations('title', [
            'en' => 'AI',
            'da' => 'AI',
        ]);
        $page->setTranslations('slug', [
            'en' => '/' . Str::slug('AI'),
            'da' => '/' . Str::slug('AI'),
        ]);
        $page->save();
    }
}
