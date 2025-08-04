<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\BookGenre;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BookGenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languageCodes = Language::get()->map(function ($language) {
            return $language['code'];
        });
        $allTranslations = [];
        foreach ($languageCodes as $code) {
            $allTranslations[$code] = 'uncategorized';
        }
        $bookGenre = new BookGenre();
        $bookGenre->setTranslations('name', $allTranslations);
        $bookGenre->setTranslations('slug', $allTranslations);
        $bookGenre->save();
    }
}
