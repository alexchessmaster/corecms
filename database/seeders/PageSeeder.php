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
    }
}
