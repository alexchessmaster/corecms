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
        $languages = Language::all(); // Add more languages as needed
        $articlePrefix = Setting::where('key', 'article-prefix')->value('value');

        $defaultFrequencyChangePages = Setting::where('key', 'default-sitemap-change-frequency-pages')->value('value');
        $defaultFrequencyChangeArticles = Setting::where('key', 'default-sitemap-change-frequency-articles')->value('value');
        $defaultPriorityPages = Setting::where('key', 'default-sitemap-priority-pages')->value('value');
        $defaultPriorityArticles = Setting::where('key', 'default-sitemap-priority-articles')->value('value');

        $frontendBaseUrl = '';
        $tables = ['pages', 'articles', 'books'];
        foreach ($languages as $language) {
            $lang = $language->code;
            // dd($lang);
            $sitemap = Sitemap::create();
            foreach ($tables as $table) {
                $pages = $this->getPagesOrArticlesForLanguage($table, $lang);
                $frontendBaseUrl = $language->domain;
                // var_dump($pages);
                foreach ($pages as $page) {
                    if (array_key_exists('slug', $page)) {
                        if ($language->use_separate_domain) {
                            $url = Url::create("{$frontendBaseUrl}{$page['slug']}");
                            // Add alternate links for all available translations
                            foreach ($page['alternates'] as $altLang => $altSlug) {
                                $langTmp = $languages->where('code', $altLang)->first();
                                $url->addAlternate("{$langTmp->domain}{$altSlug}", $altLang);
                            }
                        } else {
                            $url = Url::create("{$frontendBaseUrl}/{$lang}{$page['slug']}");
                            // Add alternate links for all available translations
                            foreach ($page['alternates'] as $altLang => $altSlug) {
                                $url->addAlternate("{$frontendBaseUrl}/{$altLang}{$altSlug}", $altLang);
                            }
                        }

                        $item = $page['item'];
                        $url->setPriority(floatval($item->sitemap_priority ?? $defaultPriorityPages));
                        $url->setChangeFrequency($item->sitemap_change_frequency ?? $defaultFrequencyChangePages);
                        $url->setLastModificationDate(Carbon::createFromFormat('Y-m-d H:i:s', $item->updated_at));
                        $sitemap->add($url);
                    }
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
            ->where('status', 'published')
            ->whereNull('sitemap_exclude')
            ->orWhere('sitemap_exclude', false)
            ->get()
            ->map(function ($pageOrArticle) use ($lang) {
                // Decode the slug JSON safely
                $slugs = json_decode($pageOrArticle->slug, true);
                if (array_key_exists($lang, $slugs)) {
                    return [
                        'slug' => $slugs[$lang], // Get the slug for the current language
                        'alternates' => collect($slugs)->filter(), // Remove null or empty slugs
                        'item' => $pageOrArticle,
                    ];
                } else {
                    return [];
                }
            });
    }

    private function generateSitemapIndex($languages, $baseUrl)
    {
        $sitemapIndex = new \Spatie\Sitemap\SitemapIndex();

        foreach ($languages as $language) {
            if ($language->use_separate_domain) {
                $sitemapIndex->add("{$language->domain}/sitemap-{$language->code}.xml");
            } else {
                $sitemapIndex->add("{$baseUrl}/sitemap-{$language->code}.xml");
            }
        }

        $sitemapIndexPath = public_path('sitemap.xml');
        $sitemapIndex->writeToFile($sitemapIndexPath);

        $this->info("Generated sitemap index: {$sitemapIndexPath}");
    }
}
