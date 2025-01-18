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
        $setting->key = 'default-sitemap-change-frequently-pages';
        $setting->value = 'monthly';
        $setting->description = "Default sitemap change frequently pages";
        $setting->save();

        $setting = new Setting;
        $setting->key = 'default-sitemap-change-frequently-articles';
        $setting->value = 'yearly';
        $setting->description = "Default sitemap change frequently articles";
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

        Setting::insert([        
            ['key' => 'text_title_of_the_website', 'value' => 'CMS'],
            ['key' => 'text_tel_formatted', 'value' => '+45-71585844'],
            ['key' => 'tel', 'value' => '+4571585844'],
            ['key' => 'email', 'value' => 'sales@nordicstandard.net'],
        ]);
    }
}
