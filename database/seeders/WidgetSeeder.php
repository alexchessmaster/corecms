<?php

namespace Database\Seeders;

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
                'name' => 'Code',
                'key' => Str::slug('Code'),
                'image' => '/uploads/code.png',
            ],
            [
                'name' => 'Space',
                'key' => Str::slug('Space'),
                'image' => '/uploads/space.png',
            ],
            [
                'name' => 'Block Starts',
                'key' => Str::slug('Block Start'),
                'image' => '/uploads/block-starts.png',
            ],
            [
                'name' => 'Block Ends',
                'key' => Str::slug('Block End'),
                'image' => '/uploads/block-ends.png',
            ],
            [
                'name' => 'Text One Column',
                'key' => Str::slug('Text One Column'),
                'image' => '/uploads/one-column-text.png',
            ],
            [
                'name' => 'Text Two Columns',
                'key' => Str::slug('Text Two Columns'),
                'image' => '/uploads/two-columns-text.png',
            ],
            [
                'name' => 'Text Three Columns',
                'key' => Str::slug('Text Three Columns'),
                'image' => '/uploads/three-columns-text.png',
            ],
            [
                'name' => 'Image One Column',
                'key' => Str::slug('Image One Column'),
                'image' => '/uploads/one-column-image.png',
            ],
            [
                'name' => 'Image Two Columns',
                'key' => Str::slug('Image Two Columns'),
                'image' => '/uploads/two-columns-image.png',
            ],
            [
                'name' => 'Image Three Columns',
                'key' => Str::slug('Image Three Columns'),
                'image' => '/uploads/three-columns-image.png',
            ],
        ]);
    }
}
