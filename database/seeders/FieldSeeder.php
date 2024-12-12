<?php

namespace Database\Seeders;

use App\Models\Field;
use App\Models\Widget;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $field = new Field;
        $field->widget_id = 5;
        $field->type = 'text';
        $field->user_note = 'hello text';
        $field->save();
        
        $field = new Field;
        $field->widget_id = 6;
        $field->type = 'text';
        $field->user_note = 'hi text';
        $field->save();

        $field = new Field;
        $field->widget_id = 6;
        $field->type = 'text';
        $field->user_note = 'bye text';
        $field->save();

        // $field = new Field;
        // $field->widget_id = 5;
        // $field->type = 'color';
        // $field->user_note = 'bg color';
        // $field->save();

        // $field = new Field;
        // $field->widget_id = 6;
        // $field->type = 'select_option';
        // $field->user_note = 'position';
        // $field->save();
    }
}
