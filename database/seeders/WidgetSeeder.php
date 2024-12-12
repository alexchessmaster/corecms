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
                'name' => 'Code',
                'key' => Str::slug('Code'),
                'image' => '/uploads/code.png',
            ],
            [//2
                'name' => 'Space',
                'key' => Str::slug('Space'),
                'image' => '/uploads/space.png',
            ],
            [//3
                'name' => 'Block Starts',
                'key' => Str::slug('Block Start'),
                'image' => '/uploads/block-starts.png',
            ],
            [//4
                'name' => 'Block Ends',
                'key' => Str::slug('Block End'),
                'image' => '/uploads/block-ends.png',
            ],
            [//5
                'name' => 'Text One Column',
                'key' => Str::slug('Text One Column'),
                'image' => '/uploads/one-column-text.png',
            ],
            [//6
                'name' => 'Text Two Columns',
                'key' => Str::slug('Text Two Columns'),
                'image' => '/uploads/two-columns-text.png',
            ],
            [//7
                'name' => 'Text Three Columns',
                'key' => Str::slug('Text Three Columns'),
                'image' => '/uploads/three-columns-text.png',
            ],
            [//8
                'name' => 'Image One Column',
                'key' => Str::slug('Image One Column'),
                'image' => '/uploads/one-column-image.png',
            ],
            [//9
                'name' => 'Image Two Columns',
                'key' => Str::slug('Image Two Columns'),
                'image' => '/uploads/two-columns-image.png',
            ],
            [//10
                'name' => 'Image Three Columns',
                'key' => Str::slug('Image Three Columns'),
                'image' => '/uploads/three-columns-image.png',
            ],
        ]);

        $page = Page::find(1);
        $page->widgets()->attach([5 => ['position' => 0]]);
        $page->widgets()->attach([5 => ['position' => 1]]);
        $page->widgets()->attach([6 => ['position' => 2]]);
        $page->widgets()->attach([8 => ['position' => 3]]);

    }
}
