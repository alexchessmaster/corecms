<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting = new Setting;
        $setting->key = 'article-prefix';
        $setting->value = 'articles';
        $setting->description = "Can be empty or articles can be change depends on your need some websites like to have /articles before the slug of each article";
        $setting->save();

        $setting = new Setting;
        $setting->key = 'product-prefix';
        $setting->value = 'products';
        $setting->description = "Can be empty or products can be change depends on your need some websites like to have /products before the slug of each product";
        $setting->save();

        $setting = new Setting;
        $setting->key = 'book-prefix';
        $setting->value = 'books';
        $setting->description = "Can be empty or books can be change depends on your need some websites like to have /books before the slug of each book";
        $setting->save();

        $setting = new Setting;
        $setting->key = 'default-sitemap-change-frequency-pages';
        $setting->value = 'monthly';
        $setting->description = "Default sitemap change frequency pages";
        $setting->save();

        $setting = new Setting;
        $setting->key = 'default-sitemap-change-frequency-articles';
        $setting->value = 'yearly';
        $setting->description = "Default sitemap change frequency articles";
        $setting->save();

        $setting = new Setting;
        $setting->key = 'default-sitemap-priority-pages';
        $setting->value = '0.8';
        $setting->description = "Default sitemap priority for pages";
        $setting->save();

        $setting = new Setting;
        $setting->key = 'default-sitemap-priority-articles';
        $setting->value = '0.6';
        $setting->description = "Default sitemap priority for articles";
        $setting->save();
    }
}
