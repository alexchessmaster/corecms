<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'Forside',
            'en' => 'Front Page'
        ]);
        $page->setTranslations('slug', [
            'da' => Str::slug('/'),
            'en' => Str::slug('/')
        ]);
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'Tjenester',
            'en' => 'Services'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('Tjenester'),
            'en' => '/' . Str::slug('Services')
        ]);
        $page->save();

        $page = new Page;
        // $page->setTranslation('title', )
        $page->setTranslations('title', [
            'da' => 'Risikostyring',
            'en' => 'Risk management'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('Risikostyring'),
            'en' => '/' . Str::slug('Risk management')
        ]);
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'Forretningsrådgivning',
            'en' => 'Business consulting'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('Forretningsrådgivning'),
            'en' => '/' . Str::slug('Business consulting')
        ]);
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'Agile projekt- og programledelse',
            'en' => 'Agile project and program management'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('Agile projekt- og programledelse'),
            'en' => '/' . Str::slug('Agile project and program management')
        ]);
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'Near shore udvikling',
            'en' => 'Nearshore development'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('Near shore udvikling'),
            'en' => '/' . Str::slug('Nearshore development')
        ]);
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'Ledige stillinger',
            'en' => 'Vacancies'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('Ledige stillinger'),
            'en' => '/' . Str::slug('Vacancies')
        ]);
        $page->save();

        $page = new Page;
        $page->setTranslations('title', [
            'da' => 'Om os',
            'en' => 'About us'
        ]);
        $page->setTranslations('slug', [
            'da' => '/' . Str::slug('Om os'),
            'en' => '/' . Str::slug('About us')
        ]);
        $page->save();
    }
}
