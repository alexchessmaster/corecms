<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'article',
            'en' => 'article'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('/sdf8u8dufdis'),
            'en' => '/' . Str::slug('/dsfud8dufuds')
        ]);
        $page->type = 'template';
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'category',
            'en' => 'category'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('/sdf8usdfsdf8dfdidsf8'),
            'en' => '/' . Str::slug('/dssdffud8sdfdsfddsfu')
        ]);
        $page->type = 'template';
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'tag',
            'en' => 'tag'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('/sdf8u8dufdisdfsdsf8dsdf'),
            'en' => '/' . Str::slug('/dsfud8dufuddsfddfsfdsfu')
        ]);
        $page->type = 'template';
        $page->save();
    }
}
