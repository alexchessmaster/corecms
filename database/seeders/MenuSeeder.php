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
        $i = 1;
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
        $menu->order = $i++;
        $menu->save();

        $servicesMenu = new Menu;
        $servicesMenu->setTranslations('name', [
            'en' => 'Services',
            'da' => 'Services', 
        ]);
        $servicesMenu->setTranslations('link', [
            'en' => '/en/' . Str::slug('Services'), 
            'da' => '/da/' . Str::slug('Services')
        ]);
        $servicesMenu->parent_id = null;
        $servicesMenu->order = $i++;
        $servicesMenu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Web development', 
            'da' => 'Webudvikling'
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/services/' . Str::slug('Web development'), 
            'da' => '/da/services/' . Str::slug('Webudvikling')
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/1736360925965757service-01.png";
        $menu->image_alt = "web development service";
        $menu->description = "Creating dynamic websites to bring your vision alive.";
        $menu->order = $i++;
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
        $menu->order = $i++;
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
        $menu->order = $i++;
        $menu->save();

        $languageMenu = new Menu;
        $languageMenu->setTranslations('name', [
            'en' => 'Language',
            'da' => 'Sprog', 
        ]);
        $languageMenu->setTranslations('link', [
            'en' => '/en',
            'da' => '/da', 
        ]);
        $languageMenu->parent_id = null;
        $languageMenu->order = $i++;
        $languageMenu->save();
        
        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'en',
            'da' => 'en', 
        ]);
        $menu->setTranslations('link', [
            'en' => '/en',
            'da' => '/en', 
        ]);
        $menu->image = "/uploads/en.webp";
        $menu->image_alt = "English language";
        $menu->description = "English language";
        $menu->parent_id = $languageMenu->id;
        $menu->order = $i++;
        $menu->save();
        
        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'da',
            'da' => 'da',
        ]);
        $menu->setTranslations('link', [
            'en' => '/da',
            'da' => '/da',
        ]);
        $menu->image = "/uploads/da.webp";
        $menu->image_alt = "Danish language";
        $menu->description = "Danish language";
        $menu->parent_id = $languageMenu->id;
        $menu->order = $i++;
        $menu->save();
        
    }
}
