<?php

namespace Database\Seeders;

use App\Modules\Pages\Models\Page;
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
            'fa' => 'Front Page',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('/'),
        ]);
        $page->save();

        // 5
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'Web development',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('Web development'),
        ]);
        $page->save();

        // 6
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'Contact',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('Contact'),
        ]);
        $page->save();

        // 7
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'Articles',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('Articles'),
        ]);
        $page->save();

        // 8
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'App development',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('App development'),
        ]);
        $page->save();

        // 9
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'Hosting',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('Hosting'),
        ]);
        $page->save();

        // 10
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'IT consultancy',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('IT consultancy'),
        ]);
        $page->save();

        // 11
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'Secure VPN',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('Secure VPN'),
        ]);
        $page->save();

        // 12
        $page = new Page;
        $page->setTranslations('title', [
            'fa' => 'AI',
        ]);
        $page->setTranslations('slug', [
            'fa' => '/' . Str::slug('AI'),
        ]);
        $page->save();
    }
}
