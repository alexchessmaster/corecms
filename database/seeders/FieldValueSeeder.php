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
            'en' => 'ACCELERATED GROWTH',
            'da' => 'ACCELERERET VÆKST',
        ]);
        $fieldValue->save();

        $fieldValue = new FieldValue;
        $fieldValue->page_widget_id = 1;
        $fieldValue->field_id = 2;
        $fieldValue->setTranslations('value',[
            'en' => 'Empowering business with modern web tools and technologies',
            'da' => 'Styrkelse af virksomheden med moderne webværktøjer og -teknologier',
        ]);
        $fieldValue->save();

        $fieldValue = new FieldValue;
        $fieldValue->page_widget_id = 1;
        $fieldValue->field_id = 3;
        $fieldValue->setTranslations('value',[
            'en' => 'Welcome to Nordicstandard web consulting and solutions.',
            'da' => 'Velkommen til Nordicstandard webrådgivning og løsninger.',
        ]);
        $fieldValue->save();

        // $fieldValue = new FieldValue;
        // $fieldValue->page_widget_id = 3;
        // $fieldValue->field_id = 3;
        // $fieldValue->setTranslations('value',[
            //     'en' => 'english second field value two columns widget in page 1',
        //     'da' => 'danish second field value two columns widget in page 1',
        // ]);
        // $fieldValue->save();
    }
}
