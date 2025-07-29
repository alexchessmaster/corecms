<?php

namespace Database\Seeders;

use App\Models\BookGenre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bookGenre = new BookGenre();
        $bookGenre->setTranslations('name', [
            'en' => 'uncategorized',
        ]);
        $bookGenre->setTranslations('slug', [
            'en' => '/uncategorized',
        ]);
        $bookGenre->save();
    }
}
