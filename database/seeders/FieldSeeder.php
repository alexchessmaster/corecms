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
     * 
     * 
     * 
     * 
     */
    public function run(): void
    {
        $this->create(4, 'blue'); //
        $this->create(4, 'header');
        $this->create(4, 'description');
        $this->create(4, 'image_1');
        $this->create(4, 'image_2');
        $this->create(6, 'tel'); //
        $this->create(6, 'email');
        $this->create(6, 'text_left_1');
        $this->create(6, 'text_left_2');
        $this->create(6, 'text_left_3');
        $this->create(6, 'text_center_1');
        $this->create(6, 'text_center_2');
        $this->create(6, 'text_center_3');
        $this->create(6, 'text_right_1');
        $this->create(6, 'text_right_2');
        $this->create(6, 'text_right_3');
        $this->create(18, 'callback_url'); //
        $this->create(19, 'blue'); //
        $this->create(19, 'header');
        $this->create(19, 'description');
        $this->create(19, 'service_1_image');
        $this->create(19, 'service_1_title');
        $this->create(19, 'service_1_description');
        $this->create(19, 'service_1_link');
        $this->create(19, 'service_2_image');
        $this->create(19, 'service_2_title');
        $this->create(19, 'service_2_description');
        $this->create(19, 'service_2_link');
        $this->create(19, 'service_3_image');
        $this->create(19, 'service_3_title');
        $this->create(19, 'service_3_description');
        $this->create(19, 'service_3_link');
        $this->create(19, 'service_4_image');
        $this->create(19, 'service_4_title');
        $this->create(19, 'service_4_description');
        $this->create(19, 'service_4_link');
        $this->create(19, 'service_5_image');
        $this->create(19, 'service_5_title');
        $this->create(19, 'service_5_description');
        $this->create(19, 'service_5_link');
    }

    private function create($widget_id, $key, $type = 'input')
    {
        $field = new Field;
        $field->widget_id = $widget_id;
        $field->type = $type;
        $field->key = $key;
        $field->save();
    }
}
