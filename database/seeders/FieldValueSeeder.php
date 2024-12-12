<?php

namespace Database\Seeders;

use App\Models\FieldValue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FieldValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fieldValue = new FieldValue;
        $fieldValue->page_widget_id = 1;
        $fieldValue->field_id = 1;
        $fieldValue->setTranslations('value',[
            'da' => 'danish field value 1',
            'en' => 'english field value 1'
        ]);
        $fieldValue->save();

        $fieldValue = new FieldValue;
        $fieldValue->page_widget_id = 2;
        $fieldValue->field_id = 1;
        $fieldValue->setTranslations('value',[
            'da' => 'danish field value 2',
            'en' => 'english field value 2'
        ]);
        $fieldValue->save();

        $fieldValue = new FieldValue;
        $fieldValue->page_widget_id = 3;
        $fieldValue->field_id = 2;
        $fieldValue->setTranslations('value',[
            'da' => 'danish first field value two columns widget in page 1',
            'en' => 'english first field value two columns widget in page 1'
        ]);
        $fieldValue->save();

        // $fieldValue = new FieldValue;
        // $fieldValue->page_widget_id = 3;
        // $fieldValue->field_id = 3;
        // $fieldValue->setTranslations('value',[
        //     'da' => 'danish second field value two columns widget in page 1',
        //     'en' => 'english second field value two columns widget in page 1'
        // ]);
        // $fieldValue->save();
    }
}
