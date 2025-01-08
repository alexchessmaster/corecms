<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Language;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Console\Command;

class GenerateSitemapsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate sitemaps';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $languages = Language::pluck('code')->all(); // Add more languages as needed
        $frontendBaseUrl = config('app.frontend_url'); // Your site's base URL
        foreach ($languages as $lang) {
            $sitemap = Sitemap::create();
            $pages = $this->getPagesForLanguage($lang);
            // var_dump($pages);
            foreach ($pages as $page) {
                if(array_key_exists('slug', $page)){

                    $url = Url::create("{$frontendBaseUrl}/{$lang}{$page['slug']}");
                    // Add alternate links for all available translations
                    foreach ($page['alternates'] as $altLang => $altSlug) {
                        $url->addAlternate("{$frontendBaseUrl}/{$altLang}{$altSlug}", $altLang);
                    }
                    $sitemap->add($url);
                }
            }
            $sitemapPath = public_path("sitemap-{$lang}.xml");
            $sitemap->writeToFile($sitemapPath);
            $this->info("Generated sitemap for {$lang}: {$sitemapPath}");
        }

        // Generate Sitemap Index
        $this->generateSitemapIndex($languages, $frontendBaseUrl);

        return 0;
    }

    private function getPagesForLanguage($lang)
    {
    //     var_dump(
    //         Article::whereNull('exclude_from_sitemap')
    // ->orWhereIn('exclude_from_sitemap', [false])
    // ->toSql()
    //         // \DB::table('articles')->
    //         // where('exclude_from_sitemap', '!=', true)->get()
    //     );die;
        // Fetch articles with available slugs for the given language
        return \DB::table('articles')
            ->whereNull('exclude_from_sitemap')
            ->orWhere('exclude_from_sitemap', false)
            ->get()
            ->map(function ($article) use ($lang) {
                // Decode the slug JSON safely
                $slugs = json_decode($article->slug, true);
                // var_dump($article);die;
                if(array_key_exists($lang, $slugs)){
                    return [
                        'slug' => $slugs[$lang], // Get the slug for the current language
                        'alternates' => collect($slugs)->filter() // Remove null or empty slugs
                    ];
                } else {
                    return [];
                }
            });
    }

    private function generateSitemapIndex($languages, $baseUrl)
    {
        $sitemapIndex = new \Spatie\Sitemap\SitemapIndex();

        foreach ($languages as $lang) {
            $sitemapIndex->add("{$baseUrl}/sitemap-{$lang}.xml");
        }

        $sitemapIndexPath = public_path('sitemap.xml');
        $sitemapIndex->writeToFile($sitemapIndexPath);

        $this->info("Generated sitemap index: {$sitemapIndexPath}");
    }
}
