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
            [//1
                'name' => 'Article',
                'key' => Str::slug('Article'),
                'image' => '/uploads/article_widget.jpeg',
                'type' => 'template',
            ],
            [//2
                'name' => 'Category',
                'key' => Str::slug('Category'),
                'image' => '/uploads/category_widget.jpeg',
                'type' => 'template',
            ],
            [//3
                'name' => 'Tag',
                'key' => Str::slug('Tag'),
                'image' => '/uploads/tag_widget.jpeg',
                'type' => 'template',
            ],
        ]);

        Widget::insert([
            [//4
                'name' => 'Header large',
                'key' => Str::slug('Header large'),
                'image' => '/uploads/nordicstandard/widgets/header-large-widget.png',
            ],
            [//5
                'name' => 'Header Small',
                'key' => Str::slug('Header Small'),
                'image' => '/uploads/header_small_widget.jpeg',
            ],
            // [//6 // put it the TranslationText because we need it everywhere we don't need to add it everywhere like menu
            //     'name' => 'Footer',
            //     'key' => Str::slug('Footer'),
            //     'image' => '/uploads/widgets/footer-widget.png',
            // ],
        ]);
        
        Widget::insert([
            [//6
                'name' => 'Code',
                'key' => Str::slug('Code'),
                'image' => '/uploads/code.png',
            ],
            [//7
                'name' => 'Space',
                'key' => Str::slug('Space'),
                'image' => '/uploads/space.png',
            ],
            [//8
                'name' => 'Block Starts',
                'key' => Str::slug('Block Start'),
                'image' => '/uploads/block-starts.png',
            ],
            [//9
                'name' => 'Block Ends',
                'key' => Str::slug('Block End'),
                'image' => '/uploads/block-ends.png',
            ],
            [//10
                'name' => 'Text One Column',
                'key' => Str::slug('Text One Column'),
                'image' => '/uploads/one-column-text.png',
            ],
            [//11
                'name' => 'Text Two Columns',
                'key' => Str::slug('Text Two Columns'),
                'image' => '/uploads/two-columns-text.png',
            ],
            [//12
                'name' => 'Text Three Columns',
                'key' => Str::slug('Text Three Columns'),
                'image' => '/uploads/three-columns-text.png',
            ],
            [//13
                'name' => 'Image One Column',
                'key' => Str::slug('Image One Column'),
                'image' => '/uploads/one-column-image.png',
            ],
            [//14
                'name' => 'Image Two Columns',
                'key' => Str::slug('Image Two Columns'),
                'image' => '/uploads/two-columns-image.png',
            ],
            [//15
                'name' => 'Image Three Columns',
                'key' => Str::slug('Image Three Columns'),
                'image' => '/uploads/three-columns-image.png',
            ],
        ]);

        Widget::insert([
            [//16
                'name' => 'article_list',
                'key' => Str::slug('article_list'),
                'image' => '/uploads/article_list_widget.jpeg',
                'type' => 'page',
            ],
        ]);

        Widget::insert([
            [//17
                'name' => 'Contact form',
                'key' => Str::slug('Contact-form'),
                'image' => '/uploads/nordicstandard/widgets/contact-form-widget.png',
                'type' => 'page',
            ],
            [//18
                'name' => 'Our service',
                'key' => Str::slug('Our service'),
                'image' => '/uploads/nordicstandard/widgets/our-service-widget.png',
                'type' => 'page',
            ],
            [//19
                'name' => 'Specialization',
                'key' => Str::slug('Specialization'),
                'image' => '/uploads/nordicstandard/widgets/specialization-widget.png',
                'type' => 'page',
            ],
            [//20
                'name' => 'How We Do',
                'key' => Str::slug('How We Do'),
                'image' => '/uploads/nordicstandard/widgets/how-we-do-widget.png',
                'type' => 'page',
            ],
            [//21
                'name' => 'Service Area',
                'key' => Str::slug('Service Area'),
                'image' => '/uploads/nordicstandard/widgets/service-area-widget.png',
                'type' => 'page',
            ],
        ]);

        // PageSeeder and FieldSeeder and WidgetSeeder 
        $page = Page::find(4);
        $page->widgets()->attach([4 => ['position' => 0]]); // 1
        $page->widgets()->attach([17 => ['position' => 1]]); // 2
        $page->widgets()->attach([18 => ['position' => 2]]); // 3
        $page->widgets()->attach([19 => ['position' => 3]]); // 4
        
        $page = Page::find(7);
        $page->widgets()->attach([16 => ['position' => 0]]); // 5

        $page = Page::find(5);
        $page->widgets()->attach([5 => ['position' => 0]]); // 6
        $page->widgets()->attach([20 => ['position' => 1]]); // 7
        $page->widgets()->attach([21 => ['position' => 2]]); // 8

    }
}
