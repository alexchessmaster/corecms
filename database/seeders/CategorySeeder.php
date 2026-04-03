<?php

namespace Database\Seeders;

use App\Modules\Articles\Models\Category;
use App\Models\Language;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languageCodes = Language::get()->map(function ($language) {
            return $language['code'];
        });
        $allTranslations = [];
        foreach ($languageCodes as $code) {
            $allTranslations[$code] = 'uncategorized';
        }
        $category = new Category();
        $category->setTranslations('name', $allTranslations);
        $category->setTranslations('slug', $allTranslations);
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
