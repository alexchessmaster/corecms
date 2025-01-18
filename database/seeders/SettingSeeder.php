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

        Setting::insert([        
            ['key' => 'text_title_of_the_website', 'value' => 'CMS'],
            ['key' => 'text_tel_formatted', 'value' => '+45-71585844'],
            ['key' => 'tel', 'value' => '+4571585844'],
            ['key' => 'email', 'value' => 'sales@nordicstandard.net'],
        ]);
    }
}
