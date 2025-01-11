<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $article = new Article();
        $article->setTranslations('title', [
            'en' => 'Why the sky is blue',
            'da' => 'Hvofor hemlen er blå'
        ]);
        $article->setTranslations('description', [
            'en' => 'Why the sky is blue description',
            'da' => 'Hvofor hemlen er blå description'
        ]);
        $article->setTranslations('content', [
            'en' => '<p>Why the sky is blue long content</p>',
            'da' => '<p>Hvofor hemlen er blå langt inhold</p>'
        ]);
        $article->category_id = 1;
        $article->template_page_id = 1;
        $article->save();
    }
}
