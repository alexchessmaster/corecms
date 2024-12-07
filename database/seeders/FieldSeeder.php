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
        $field->widget_id = 1;
        $field->type = 'text';
        $field->setTranslations('value', [
            'en' => 'hello',
            'da' => 'hej',
        ]);
        $field->user_note = 'hello text';
        $field->save();
        
        $field = new Field;
        $field->widget_id = 2;
        $field->type = 'text';
        $field->setTranslations('value', [
            'en' => 'Bye',
            'da' => 'Farvel',
        ]);
        $field->save();

        $field = new Field;
        $field->widget_id = 1;
        $field->type = 'color';
        $field->setTranslations('value', [
            'en' => '#00ff00',
            'da' => '#00ff00',
        ]);
        $field->save();

        $field = new Field;
        $field->widget_id = 3;
        $field->type = 'select_option';
        $field->setTranslations('value', [
            'en' => 'left,center,right',
            'da' => 'left,center,right',
        ]);
        $field->save();
    }
}
