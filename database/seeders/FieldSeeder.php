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
        $field->widget_id = 11;
        $field->type = 'text';
        $field->user_note = 'hello text';
        $field->save();
        
        $field = new Field;
        $field->widget_id = 12;
        $field->type = 'text';
        $field->user_note = 'hi text';
        $field->save();

        $field = new Field;
        $field->widget_id = 12;
        $field->type = 'text';
        $field->user_note = 'bye text';
        $field->save();
    }
}
