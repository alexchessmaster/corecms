<?php

namespace Database\Seeders;

use App\Modules\Widgets\Models\Widgetable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WidgetableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Widgetable::insert([
            ['widget_id' => 1, 'widgetable_id' => 1, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 1],
            ['widget_id' => 2, 'widgetable_id' => 1, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 2],
            ['widget_id' => 3, 'widgetable_id' => 1, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 3],
            ['widget_id' => 4, 'widgetable_id' => 1, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 4],
            ['widget_id' => 5, 'widgetable_id' => 1, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 5],
            ['widget_id' => 6, 'widgetable_id' => 1, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 6],
            ['widget_id' => 7, 'widgetable_id' => 1, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 7],
            ['widget_id' => 4, 'widgetable_id' => 2, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 1],
            ['widget_id' => 5, 'widgetable_id' => 2, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 2],
            ['widget_id' => 6, 'widgetable_id' => 2, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 3],
            ['widget_id' => 2, 'widgetable_id' => 3, 'widgetable_type' => 'App\Modules\Pages\Models\Page', 'position' => 1],
        ]);
    }
}
