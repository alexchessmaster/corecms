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
        ]);
        $category->setTranslations('slug', [
            'en' => '/uncategorized',
        ]);
        $category->save();

        $category = new Category();
        $category->setTranslations('name', [
            'fa' => 'car',
        ]);
        $category->setTranslations('slug', [
            'fa' => '/car',
        ]);
        $category->save();

        $category = new Category();
        $category->setTranslations('name', [
            'fa' => 'building',
        ]);
        $category->setTranslations('slug', [
            'fa' => '/building',
        ]);
        $category->save();

        $category = new Category();
        $category->setTranslations('name', [
            'fa' => 'nature',
        ]);
        $category->setTranslations('slug', [
            'fa' => 'nature',
        ]);
        $category->save();
    }
}
