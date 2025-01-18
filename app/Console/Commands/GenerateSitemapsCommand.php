<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\Setting;
use App\Models\Language;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Support\Carbon;
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

        $articlePrefix = Setting::where('key', 'article-prefix')->value('value');

        $defaultFrequentlyChangePages = Setting::where('key', 'default-sitemap-change-frequently-pages')->value('value');
        $defaultFrequentlyChangeArticles = Setting::where('key', 'default-sitemap-change-frequently-articles')->value('value');
        $defaultPriorityPages = Setting::where('key', 'default-sitemap-priority-pages')->value('value');
        $defaultPriorityArticles = Setting::where('key', 'default-sitemap-priority-articles')->value('value');

        // 'sitemap_exclude', 'sitemap_priority', 'sitemap_change_frequently'
        // dd($defaultFrequentlyChangePages);
        foreach ($languages as $lang) {
            $sitemap = Sitemap::create();
            $table = 'pages';
            $pages = $this->getPagesOrArticlesForLanguage($table, $lang);
            // var_dump($pages);
            foreach ($pages as $page) {
                if (array_key_exists('slug', $page)) {
                    $url = Url::create("{$frontendBaseUrl}/{$lang}{$page['slug']}");
                    // Add alternate links for all available translations
                    foreach ($page['alternates'] as $altLang => $altSlug) {
                        $url->addAlternate("{$frontendBaseUrl}/{$altLang}{$altSlug}", $altLang);
                    }
                    $item = $page['item'];
                    $url->setPriority(floatval($item->sitemap_priority ?? $defaultPriorityPages));
                    $url->setChangeFrequency($item->sitemap_change_frequently ?? $defaultFrequentlyChangePages);
                    $url->setLastModificationDate(Carbon::createFromFormat('Y-m-d H:i:s', $item->updated_at));
                    $sitemap->add($url);
                }
            }
            $table = 'articles';
            $pages = $this->getPagesOrArticlesForLanguage($table, $lang);
            // var_dump($pages);
            foreach ($pages as $page) {
                if (array_key_exists('slug', $page)) {
                    $url = Url::create("{$frontendBaseUrl}/{$lang}{$page['slug']}");
                    // Add alternate links for all available translations
                    foreach ($page['alternates'] as $altLang => $altSlug) {
                        $url->addAlternate("{$frontendBaseUrl}/{$altLang}{$altSlug}", $altLang);
                    }
                    $item = $page['item'];
                    $url->setPriority(floatval($item->sitemap_priority ?? $defaultPriorityPages));
                    $url->setChangeFrequency($item->sitemap_change_frequently ?? $defaultFrequentlyChangePages);
                    $url->setLastModificationDate(Carbon::createFromFormat('Y-m-d H:i:s', $item->updated_at));
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

    private function getPagesOrArticlesForLanguage($table, $lang)
    {
        // Fetch articles with available slugs for the given language
        return \DB::table($table)
            ->whereNull('sitemap_exclude')
            ->orWhere('sitemap_exclude', false)
            ->get()
            ->map(function ($article) use ($lang) {
                // Decode the slug JSON safely
                $slugs = json_decode($article->slug, true);
                if (array_key_exists($lang, $slugs)) {
                    return [
                        'slug' => $slugs[$lang], // Get the slug for the current language
                        'alternates' => collect($slugs)->filter(), // Remove null or empty slugs
                        'item' => $article,
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
