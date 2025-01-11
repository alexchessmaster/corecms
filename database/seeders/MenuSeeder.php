<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Home',
            'da' => 'Hjem', 
        ]);
        $menu->setTranslations('link', [
            'en' => '/en',
            'da' => '/da',
        ]);
        $menu->parent_id = null;
        $menu->order = 1;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Services',
            'da' => 'Services', 
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/' . Str::slug('Services'), 
            'da' => '/da/' . Str::slug('Services')
        ]);
        $menu->parent_id = null;
        $menu->order = 2;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Web development', 
            'da' => 'Webudvikling'
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/services/' . Str::slug('Web development'), 
            'da' => '/da/services/' . Str::slug('Webudvikling')
        ]);
        $menu->parent_id = 2;
        $menu->image = "/uploads/1736360925965757service-01.png";
        $menu->image_alt = "web development service";
        $menu->description = "Creating dynamic websites to bring your vision alive.";
        $menu->order = 3;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Contact us',
            'da' => 'Kontakt os', 
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/' . Str::slug('Contact'),
            'da' => '/da/' . Str::slug('Kontakt'), 
        ]);
        $menu->parent_id = null;
        $menu->order = 4;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Articles',
            'da' => 'Artikler', 
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/' . Str::slug('Articles'),
            'da' => '/da/' . Str::slug('Artikler'), 
        ]);
        $menu->parent_id = null;
        $menu->order = 4;
        $menu->save();
    }
}
