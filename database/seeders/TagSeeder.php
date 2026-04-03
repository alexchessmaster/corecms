<?php

namespace Database\Seeders;

use App\Modules\Articles\Models\Tag;
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
            'fa' => 'tree'
        ]);
        $tag->save();

        $tag = new Tag();
        $tag->setTranslations('name', [
            'fa' => 'flower'
        ]);
        $tag->save();

        $tag = new Tag();
        $tag->setTranslations('name', [
            'fa' => 'book'
        ]);
        $tag->save();
    }
}
