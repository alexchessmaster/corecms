<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tag = new Tag();
        $tag->setTranslations('name', [
            'da' => 'tre',
            'en' => 'tree'
        ]);
        $tag->template_page_id = 3;
        $tag->save();
        
        $tag = new Tag();
        $tag->setTranslations('name', [
            'da' => 'blomst',
            'en' => 'flower'
        ]);
        $tag->template_page_id = 3;
        $tag->save();
        
        $tag = new Tag();
        $tag->setTranslations('name', [
            'da' => 'bog',
            'en' => 'book'
        ]);
        $tag->template_page_id = 3;
        $tag->save();
    }
}
