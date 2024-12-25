<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $category = new Category();
        $category->setTranslations('name', [
            'en' => 'uncategorized',
            'da' => 'uncategorized'
        ]);
        $category->template_page_id = 2;
        $category->save();

        $category = new Category();
        $category->setTranslations('name', [
            'en' => 'car',
            'da' => 'bil'
        ]);
        $category->template_page_id = 2;
        $category->save();

        $category = new Category();
        $category->setTranslations('name', [
            'en' => 'building',
            'da' => 'bolig'
        ]);
        $category->template_page_id = 2;
        $category->save();

        $category = new Category();
        $category->setTranslations('name', [
            'en' => 'nature',
            'da' => 'natur'
        ]);
        $category->template_page_id = 2;
        $category->save();
    }
}
