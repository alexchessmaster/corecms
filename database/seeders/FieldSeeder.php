<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Field;
use App\Models\Widget;
use App\Models\Language;
use App\Models\FieldValue;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
        Field::insert([
            ['type' => 'input'],
            ['type' => 'textarea_vanilla'],
            ['type' => 'textarea_one_line'],
            ['type' => 'textarea_small'],
            ['type' => 'textarea_large'],
            ['type' => 'file'],
            ['type' => 'color'],
            ['type' => 'code'],
            ['type' => 'select_option_left_center_right'],
            ['type' => 'select_option_on_off'],
        ]);
    }
}
