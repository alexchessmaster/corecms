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
        $field->type = 'input';
        $field->key = 'hello text';
        $field->save();
        
        $field = new Field;
        $field->widget_id = 12;
        $field->type = 'input';
        $field->key = 'hi text';
        $field->save();

        $field = new Field;
        $field->widget_id = 12;
        $field->type = 'input';
        $field->key = 'bye text';
        $field->save();
    }
}
