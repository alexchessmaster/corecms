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
            [ //1
                'name' => 'Article',
                'key' => Str::slug('Article'),
                'image' => '/uploads/article_widget.jpeg',
                'type' => 'template',
            ],
            [ //2
                'name' => 'Category',
                'key' => Str::slug('Category'),
                'image' => '/uploads/category_widget.jpeg',
                'type' => 'template',
            ],
            [ //3
                'name' => 'Tag',
                'key' => Str::slug('Tag'),
                'image' => '/uploads/tag_widget.jpeg',
                'type' => 'template',
            ],
        ]);

        Widget::insert([
            [ //4
                'name' => 'Header large',
                'key' => Str::slug('Header large'),
                'image' => '/uploads/nordicstandard/widgets/header-large-widget.png',
            ],
            [ //5
                'name' => 'Header Small',
                'key' => Str::slug('Header Small'),
                'image' => '/uploads/nordicstandard/header-small-widget.png',
            ],
            // [//6 // put it the TranslationText because we need it everywhere we don't need to add it everywhere like menu
            //     'name' => 'Footer',
            //     'key' => Str::slug('Footer'),
            //     'image' => '/uploads/widgets/footer-widget.png',
            // ],
        ]);

        Widget::insert([
            [ //6
                'name' => 'Code',
                'key' => Str::slug('Code'),
                'image' => '/uploads/code.png',
            ],
            [ //7
                'name' => 'Space',
                'key' => Str::slug('Space'),
                'image' => '/uploads/space.png',
            ],
            [ //8
                'name' => 'Block Starts',
                'key' => Str::slug('Block Start'),
                'image' => '/uploads/block-starts.png',
            ],
            [ //9
                'name' => 'Block Ends',
                'key' => Str::slug('Block End'),
                'image' => '/uploads/block-ends.png',
            ],
            [ //10
                'name' => 'Text One Column',
                'key' => Str::slug('Text One Column'),
                'image' => '/uploads/one-column-text.png',
            ],
            [ //11
                'name' => 'Text Two Columns',
                'key' => Str::slug('Text Two Columns'),
                'image' => '/uploads/two-columns-text.png',
            ],
            [ //12
                'name' => 'Text Three Columns',
                'key' => Str::slug('Text Three Columns'),
                'image' => '/uploads/three-columns-text.png',
            ],
            [ //13
                'name' => 'Image One Column',
                'key' => Str::slug('Image One Column'),
                'image' => '/uploads/one-column-image.png',
            ],
            [ //14
                'name' => 'Image Two Columns',
                'key' => Str::slug('Image Two Columns'),
                'image' => '/uploads/two-columns-image.png',
            ],
            [ //15
                'name' => 'Image Three Columns',
                'key' => Str::slug('Image Three Columns'),
                'image' => '/uploads/three-columns-image.png',
            ],
        ]);

        Widget::insert([
            [ //16
                'name' => 'article_list',
                'key' => Str::slug('article_list'),
                'image' => '/uploads/nordicstandard/article-list-widget.png',
                'type' => 'page',
            ],
        ]);

        Widget::insert([
            [ //17
                'name' => 'Contact form',
                'key' => Str::slug('Contact-form'),
                'image' => '/uploads/nordicstandard/widgets/contact-form-widget.png',
                'type' => 'page',
                // 'locked_fields_value' => 1,
            ],
            [ //18
                'name' => 'Our service',
                'key' => Str::slug('Our service'),
                'image' => '/uploads/nordicstandard/widgets/our-service-widget.png',
                'type' => 'page',
            ],
            [ //19
                'name' => 'Specialization',
                'key' => Str::slug('Specialization'),
                'image' => '/uploads/nordicstandard/widgets/specialization-widget.png',
                'type' => 'page',
            ],
            [ //20
                'name' => 'How We Do',
                'key' => Str::slug('How We Do'),
                'image' => '/uploads/nordicstandard/widgets/how-we-do-widget.png',
                'type' => 'page',
            ],
            [ //21
                'name' => 'Service Area',
                'key' => Str::slug('Service Area'),
                'image' => '/uploads/nordicstandard/widgets/service-area-widget.png',
                'type' => 'page',
            ],
            [ //22 https://wpriverthemes.com/synck/new-releases/
                'name' => 'Header Small Dark',
                'key' => Str::slug('Header Small dark'),
                'image' => '/uploads/nordicstandard/widgets/header-small-dark-widget.png',
                'type' => 'page',
            ],
            [ //23 https://wpriverthemes.com/synck/events/
                'name' => 'Image Text',
                'key' => Str::slug('Image Text'),
                'image' => '/uploads/nordicstandard/widgets/image-text-widget.png',
                'type' => 'page',
            ],
            [ //24 https://wpriverthemes.com/synck/company/
                'name' => 'Image Text Dark',
                'key' => Str::slug('Image Text dark'),
                'image' => '/uploads/nordicstandard/widgets/image-text-dark-widget.png',
                'type' => 'page',
            ],
            [ //25 https://wpriverthemes.com/synck/our-pricing/
                'name' => 'Price',
                'key' => Str::slug('Price'),
                'image' => '/uploads/nordicstandard/widgets/price-widget.png',
                'type' => 'page',
            ],
            [ //26 https://wpriverthemes.com/synck/company/
                'name' => 'Header Image Content',
                'key' => Str::slug('Header Image Content'),
                'image' => '/uploads/nordicstandard/widgets/header-image-content-widget.png',
                'type' => 'page',
            ],
        ]);

        $deActiveWidgets = [6, 8, 9, 11, 12, 13, 14, 15];
        foreach ($deActiveWidgets as $id) {
            $widget = Widget::find($id);
            $widget->active = false;
            $widget->save();
        }

        $widget = Widget::find(17);
        $widget->locked_fields_value = true;
        $widget->save();

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

        $page = Page::find(6);
        $page->widgets()->attach([5 => ['position' => 0]]); // 9
        $page->widgets()->attach([7 => ['position' => 1]]); // 10
        $page->widgets()->attach([17 => ['position' => 2]]); // 11

        $page = Page::find(8);
        $page->widgets()->attach([22 => ['position' => 0]]); // 12
        $page->widgets()->attach([26 => ['position' => 1]]); // 13
        $page->widgets()->attach([25 => ['position' => 2]]); // 14
        $page->widgets()->attach([24 => ['position' => 3]]); // 15
        $page->widgets()->attach([23 => ['position' => 4]]); // 16
        
        $page = Page::find(9);
        $page->widgets()->attach([22 => ['position' => 0]]); // 17
        $page->widgets()->attach([26 => ['position' => 1]]); // 18
        $page->widgets()->attach([25 => ['position' => 2]]); // 19
        $page->widgets()->attach([24 => ['position' => 3]]); // 20
        $page->widgets()->attach([23 => ['position' => 4]]); // 21
        
        $page = Page::find(10);
        $page->widgets()->attach([22 => ['position' => 0]]); // 22
        $page->widgets()->attach([26 => ['position' => 1]]); // 23
        $page->widgets()->attach([25 => ['position' => 2]]); // 24
        $page->widgets()->attach([24 => ['position' => 3]]); // 25
        $page->widgets()->attach([23 => ['position' => 4]]); // 26

        $page = Page::find(11);
        $page->widgets()->attach([22 => ['position' => 0]]); // 27
        $page->widgets()->attach([26 => ['position' => 1]]); // 28
        $page->widgets()->attach([25 => ['position' => 2]]); // 29
        $page->widgets()->attach([24 => ['position' => 3]]); // 30
        $page->widgets()->attach([23 => ['position' => 4]]); // 31

        $page = Page::find(12);
        $page->widgets()->attach([22 => ['position' => 0]]); // 32
        $page->widgets()->attach([26 => ['position' => 1]]); // 33
        $page->widgets()->attach([25 => ['position' => 2]]); // 34
        $page->widgets()->attach([24 => ['position' => 3]]); // 35
        $page->widgets()->attach([23 => ['position' => 4]]); // 36

    }
}
