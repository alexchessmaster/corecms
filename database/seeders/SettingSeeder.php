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
        Setting::insert([
            ['key' => 'article-prefix', 'value' => 'articles', 'description' => "Can be empty or 'articles' can be change depends on your need some websites like to have /articles before the slug of each article"],

            ['key' => 'title_of_the_website', 'value' => 'CMS'],
            ['key' => 'tel_formatted', 'value' => '+45-71585844'],
            ['key' => 'tel', 'value' => '+4571585844'],
            ['key' => 'email', 'value' => 'sales@nordicstandard.net'],
            ['key' => 'navbar_text_email_us', 'value' => 'Email Us'],
            ['key' => 'footer_text_header', 'value' => 'Contact us'],
            ['key' => 'footer_text_tel_header', 'value' => 'Phone:'],
            ['key' => 'footer_text_mail_header', 'value' => 'Mail:'],
            ['key' => 'footer_text_1_1', 'value' => '1'],
            ['key' => 'footer_text_1_2', 'value' => 'day'],
            ['key' => 'footer_text_1_3', 'value' => 'Response Time'],
            ['key' => 'footer_text_2_1', 'value' => '99%'],
            ['key' => 'footer_text_2_2', 'value' => ''],
            ['key' => 'footer_text_2_3', 'value' => 'Client Satisfaction'],
            ['key' => 'footer_text_3_1', 'value' => '11+'],
            ['key' => 'footer_text_3_2', 'value' => 'Years'],
            ['key' => 'footer_text_3_3', 'value' => 'Field Experience'],
        ]);
    }
}
