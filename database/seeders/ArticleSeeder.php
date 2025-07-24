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
            'fa' => 'Why the sky is blue',
        ]);
        $article->setTranslations('slug', [
            'fa' => 'why-the-sky-is-blue',
        ]);
        $article->setTranslations('description', [
            'fa' => 'Why the sky is blue description',
        ]);
        $article->category_id = 1;
        $article->save();
    }
}
