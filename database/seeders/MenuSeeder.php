<?php

namespace Database\Seeders;

use App\Modules\Menus\Models\Menu;
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
            'fa' => 'Home',
        ]);
        $menu->setTranslations('link', [
            'fa' => '/fa',
        ]);
        $menu->parent_id = null;
        $menu->order = $i++;
        $menu->save();

        $servicesMenu = new Menu;
        $servicesMenu->setTranslations('name', [
            'fa' => 'Services',
        ]);
        $servicesMenu->setTranslations('link', [
            'fa' => '#', //Str::slug('Services'),
        ]);
        $servicesMenu->parent_id = null;
        $servicesMenu->order = $i++;
        $servicesMenu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'Web development',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('Web development'),
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-01.png";
        $menu->image_alt = "web development service";
        $menu->setTranslations('description', [
            'fa' => "We design and develop responsive, user-friendly websites tailored to your business needs. From simple landing pages to functional websites, we deliver quality solutions that represent your brand effectively.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'App development',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('App development'),
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-02.jpg";
        $menu->image_alt = "App development service";
        $menu->setTranslations('description', [
            'fa' => "Get custom mobile and desktop applications that are intuitive and reliable. Whether for Android, iOS, or cross-platform, we create apps designed to enhance user experience and drive engagement.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'Hosting',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('Hosting'),
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-03.png";
        $menu->image_alt = "Hosting service";
        $menu->setTranslations('description', [
            'fa' => "Reliable hosting solutions to keep your website running smoothly. We provide secure, fast, and scalable hosting with minimal downtime to ensure your business is always accessible online.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'IT consultancy',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('IT consultancy'),
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-04.png";
        $menu->image_alt = "IT consultancy service";
        $menu->setTranslations('description', [
            'fa' => "Expert IT guidance to help you make informed technology decisions. We assist in strategy, infrastructure planning, and troubleshooting to optimize your operations efficiently.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'Secure VPN',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('Secure VPN'),
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-05.jpg";
        $menu->image_alt = "Secure VPN service";
        $menu->setTranslations('description', [
            'fa' => "Protect your data with our secure VPN services. Safeguard your online activities with encrypted connections for enhanced privacy and secure browsing.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'AI',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('AI'),
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-06.png";
        $menu->image_alt = "AI service";
        $menu->setTranslations('description', [
            'fa' => "We harness the power of Artificial Intelligence to help your business innovate, automate, and grow.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'Contact us',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('Contact'),
        ]);
        $menu->parent_id = null;
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'fa' => 'Articles',
        ]);
        $menu->setTranslations('link', [
            'fa' => Str::slug('Articles'),
        ]);
        $menu->parent_id = null;
        $menu->order = $i++;
        $menu->save();

    }
}
