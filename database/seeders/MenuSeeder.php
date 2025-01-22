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
            'en' => '#', //'/en/' . Str::slug('Services'), 
            'da' => '#', // '/da/' . Str::slug('Services')
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
            'en' => '/en/' . Str::slug('Web development'), 
            'da' => '/da/' . Str::slug('Webudvikling')
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-01.png";
        $menu->image_alt = "web development service";
        $menu->setTranslations('description', [
            "en"=>"We design and develop responsive, user-friendly websites tailored to your business needs. From simple landing pages to functional websites, we deliver quality solutions that represent your brand effectively.",
            "da"=>"Vi designer og udvikler responsive, brugervenlige hjemmesider skræddersyet til din virksomheds behov. Fra enkle landingssider til funktionelle hjemmesider leverer vi kvalitetsløsninger, der effektivt repræsenterer dit brand.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'App development', 
            'da' => 'App udvikling'
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/' . Str::slug('App development'), 
            'da' => '/da/' . Str::slug('App udvikling')
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-02.jpg";
        $menu->image_alt = "App development service";
        $menu->setTranslations('description', [
            "en"=>"Get custom mobile and desktop applications that are intuitive and reliable. Whether for Android, iOS, or cross-platform, we create apps designed to enhance user experience and drive engagement.",
            "da"=>"Få skræddersyede mobil- og desktopapplikationer, der er intuitive og pålidelige. Uanset om det er til Android, iOS eller flere platforme, skaber vi apps designet til at forbedre brugeroplevelsen og øge engagementet.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Hosting', 
            'da' => 'Hosting'
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/' . Str::slug('Hosting'), 
            'da' => '/da/' . Str::slug('Hosting')
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-03.png";
        $menu->image_alt = "Hosting service";
        $menu->setTranslations('description', [
            "en"=>"Reliable hosting solutions to keep your website running smoothly. We provide secure, fast, and scalable hosting with minimal downtime to ensure your business is always accessible online.",
            "da"=>"Pålidelige hostingløsninger, der holder din hjemmeside kørende problemfrit. Vi tilbyder sikker, hurtig og skalerbar hosting med minimal nedetid, så din virksomhed altid er tilgængelig online.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'IT consultancy', 
            'da' => 'IT rådgivning'
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/' . Str::slug('IT consultancy'), 
            'da' => '/da/' . Str::slug('IT rådgivning')
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-04.png";
        $menu->image_alt = "IT consultancy service";
        $menu->setTranslations('description', [
            "en"=>"Expert IT guidance to help you make informed technology decisions. We assist in strategy, infrastructure planning, and troubleshooting to optimize your operations efficiently.",
            "da"=>"Ekspertvejledning inden for IT, der hjælper dig med at træffe velinformerede teknologiske beslutninger. Vi bistår med strategi, planlægning af infrastruktur og fejlfinding for at optimere dine operationer effektivt.",
        ]);
        $menu->order = $i++;
        $menu->save();

        $menu = new Menu;
        $menu->setTranslations('name', [
            'en' => 'Secure VPN', 
            'da' => 'Sikker VPN'
        ]);
        $menu->setTranslations('link', [
            'en' => '/en/' . Str::slug('Secure VPN'), 
            'da' => '/da/' . Str::slug('Sikker VPN')
        ]);
        $menu->parent_id = $servicesMenu->id;
        $menu->image = "/uploads/nordicstandard/service-05.jpg";
        $menu->image_alt = "Secure VPN service";
        $menu->setTranslations('description', [
            "en"=>"Protect your data with our secure VPN services. Safeguard your online activities with encrypted connections for enhanced privacy and secure browsing.",
            "da"=>"Beskyt dine data med vores sikre VPN-tjenester. Sikr dine online aktiviteter med krypterede forbindelser for øget privatliv og sikker browsing.",
        ]);
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
            'en' => '#',
            'da' => '#', 
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
        $menu->setTranslations('description', [
            "en"=>"English language",
            "da"=>"English language",
        ]);
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
        $menu->setTranslations('description', [
            "en"=>"Danish language",
            "da"=>"Danish language",
        ]);
        $menu->parent_id = $languageMenu->id;
        $menu->order = $i++;
        $menu->save();
        
    }
}
