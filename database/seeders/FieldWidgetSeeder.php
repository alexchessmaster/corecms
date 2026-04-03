<?php

namespace Database\Seeders;

use App\Modules\Widgets\Models\FieldWidget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldWidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FieldWidget::insert([
            ['widget_id' => 1, 'field_id' => 1, 'key' => 'title'],
            ['widget_id' => 1, 'field_id' => 1, 'key' => 'description'],
            ['widget_id' => 1, 'field_id' => 5, 'key' => 'image'],
            ['widget_id' => 2, 'field_id' => 1, 'key' => 'title'],
            ['widget_id' => 2, 'field_id' => 5, 'key' => 'image'],
            ['widget_id' => 3, 'field_id' => 1, 'key' => 'seo_title'],
            ['widget_id' => 3, 'field_id' => 1, 'key' => 'seo_description'],
            ['widget_id' => 4, 'field_id' => 4, 'key' => 'text'],
            ['widget_id' => 5, 'field_id' => 4, 'key' => 'text_left'],
            ['widget_id' => 5, 'field_id' => 4, 'key' => 'text_right'],
            ['widget_id' => 6, 'field_id' => 4, 'key' => 'text_left'],
            ['widget_id' => 6, 'field_id' => 4, 'key' => 'text_center'],
            ['widget_id' => 6, 'field_id' => 4, 'key' => 'text_right'],
            ['widget_id' => 7, 'field_id' => 1, 'key' => 'px'],
        ]);
    }
}
