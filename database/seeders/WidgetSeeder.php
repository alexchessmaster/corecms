<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Widget;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Widget::insert([  
            [
                'name' => 'Front Page Hero',  
                'key' => \Str::slug('Front Page Hero', '_'),
                'active' => true,
                'image' => '/uploads/header_big_widget.jpeg'
            ],
            [
                'name' => 'Page Hero',
                'key' => \Str::slug('Page Hero', '_'),
                'active' => true,
                'image' => '/uploads/header_small_widget.jpeg'
            ],
            [
                'name' => 'SEO',  
                'key' => \Str::slug('SEO', '_'),
                'active' => true,
                'image' => '/uploads/1741562319753103seo-idea-lightbulbs-ss-1920.jpg'
            ],
            [
                'name' => 'One Column Text',  
                'key' => \Str::slug('One Column Text', '_'),
                'active' => true,
                'image' => '/uploads/one-column-text.png'
            ],
            [
                'name' => 'Two Columns Text',  
                'key' => \Str::slug('Two Columns Text', '_'),
                'active' => true,
                'image' => '/uploads/two-columns-text.png'
            ],
            [
                'name' => 'Three Columns Text',  
                'key' => \Str::slug('Three Columns Text', '_'),
                'active' => true,
                'image' => '/uploads/three-columns-text.png'
            ],
            [
                'name' => 'Space',  
                'key' => \Str::slug('Space', '_'),
                'active' => true,
                'image' => '/uploads/space.png'
            ],
        ]);
        
    }
}
