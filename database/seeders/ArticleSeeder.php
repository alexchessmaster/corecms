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
            'en' => 'Why sky is blue',
            'da' => 'Hvofor hemlen er blå'
        ]);
        $article->setTranslations('content', [
            'en' => 'Why sky is blue long content',
            'da' => 'Hvofor hemlen er blå langt inhold'
        ]);
        $article->category_id = 1;
        $article->template_page_id = 1;
        $article->save();
    }
}
