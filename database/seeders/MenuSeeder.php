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
            'da' => 'Services', 
            'en' => 'services'
        ]);
        $menu->setTranslations('link', [
            'da' => '/page/' . Str::slug('Services-da'), 
            'en' => '/page/' . Str::slug('Services')
        ]);
        $menu->parent_id = null;
        $menu->order = 1;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'da' => 'Risikostyring', 
            'en' => 'Risk management'
        ]);
        $menu->setTranslations('link', [
            'da' => '/page/' . Str::slug('Risikostyring'), 
            'en' => '/page/' . Str::slug('Risk management')
        ]);
        $menu->parent_id = null;
        $menu->order = 2;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'da' => 'Forretningsrådgivning', 
            'en' => 'Business consulting'
        ]);
        $menu->setTranslations('link', [
            'da' => '/page/' . Str::slug('Forretningsrådgivning'), 
            'en' => '/page/' . Str::slug('Business consulting')
        ]);
        $menu->parent_id = null;
        $menu->order = 3;
        $menu->save();
    }
}
