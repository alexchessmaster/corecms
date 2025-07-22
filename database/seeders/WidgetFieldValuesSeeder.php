<?php

namespace Database\Seeders;

use App\Models\WidgetFieldValues;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WidgetFieldValuesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // WidgetFieldValues::create([
        //     'widget_id' => 1,
        //     'field_name' => 'title',
        //     'field_value' => 'Welcome to Our Website',
        // ]);
        WidgetFieldValues::insert([
            ['widgetable_id' => 1, 'field_widget_id' => 1, 'value' => '{"fa": "Front Page"}'],
            ['widgetable_id' => 1, 'field_widget_id' => 2, 'value' => '{"fa": "This is a sample description for the homepage."}'],
            ['widgetable_id' => 1, 'field_widget_id' => 3, 'value' => '{"fa": "/uploads/67b05e0a1e8cf.jpeg"}'],
            ['widgetable_id' => 2, 'field_widget_id' => 4, 'value' => '{"fa": "This is a test title"}'],
            ['widgetable_id' => 2, 'field_widget_id' => 5, 'value' => '{"fa": "/uploads/tag_list_widget.jpeg"}'],
        ]);
    }
}
