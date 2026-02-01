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
        $settings = Setting::get()->keyBy('key');

        $defaultFrequencyChangePages = $settings->get('default-sitemap-change-frequency-pages')->value;
        $defaultFrequencyChangeArticles = $settings->get('default-sitemap-change-frequency-articles')->value;
        $defaultFrequencyChangeBooks = $settings->get('default-sitemap-change-frequency-books')->value;
        $defaultFrequencyChangeProducts = $settings->get('default-sitemap-change-frequency-products')->value;
        $defaultFrequencyChangeNews = $settings->get('default-sitemap-change-frequency-news')->value;
        
        $defaultPriorityPages = $settings->get('default-sitemap-priority-pages')->value;
        $defaultPriorityArticles = $settings->get('default-sitemap-priority-articles')->value;
        $defaultPriorityBooks = $settings->get('default-sitemap-priority-books')->value;
        $defaultPriorityProducts = $settings->get('default-sitemap-priority-products')->value;
        $defaultPriorityNews = $settings->get('default-sitemap-priority-news')->value;

        $frontendBaseUrl = '';
        $tables = ['pages', 'articles', 'books', 'products', 'news'];
        foreach ($languages as $language) {
            $articlePrefix = $this->getPrefixSettingsValue('article-prefix', $language);
            $bookPrefix = $this->getPrefixSettingsValue('book-prefix', $language);
            $productPrefix = $this->getPrefixSettingsValue('product-prefix', $language);
            $newsPrefix = $this->getPrefixSettingsValue('news-prefix', $language);

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
                            if ($table === 'articles') {
                                $prefix = "{$articlePrefix}";
                            } elseif ($table === 'books') {
                                $prefix = "{$bookPrefix}";
                            } elseif ($table === 'products') {
                                $prefix = "{$productPrefix}";
                            } elseif ($table === 'news') {
                                $prefix = "{$newsPrefix}";
                            } else {
                                $prefix = '';
                            }
                            $url = Url::create("{$frontendBaseUrl}{$prefix}{$page['slug']}");
                            // Add alternate links for all available translations
                            foreach ($page['alternates'] as $altLang => $altSlug) {
                                $langTmp = $languages->where('code', $altLang)->first();
                                $url->addAlternate("{$langTmp->domain}{$prefix}{$altSlug}", $altLang);
                            }
                        } else {
                            if ($table === 'articles') {
                                $prefix = "{$articlePrefix}";
                            } elseif ($table === 'books') {
                                $prefix = "{$bookPrefix}";
                            } elseif ($table === 'products') {
                                $prefix = "{$productPrefix}";
                            } elseif ($table === 'news') {
                                $prefix = "{$newsPrefix}";
                            } else {
                                $prefix = '';
                            }
                            $url = Url::create("{$frontendBaseUrl}/{$lang}{$prefix}{$page['slug']}");
                            // Add alternate links for all available translations
                            foreach ($page['alternates'] as $altLang => $altSlug) {
                                $url->addAlternate("{$frontendBaseUrl}/{$altLang}{$prefix}{$altSlug}", $altLang);
                            }
                        }

                        $item = $page['item'];
                        $url->setPriority(floatval($item->sitemap_priority ?? $settings->get("default-sitemap-priority-$table")->value));
                        $url->setChangeFrequency($item->sitemap_change_frequency ?? $settings->get("default-sitemap-change-frequency-$table")->value);
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
            ->get(['id', 'slug', 'sitemap_priority', 'sitemap_change_frequency', 'updated_at'])
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

    private function getPrefixSettingsValue($settingsKey, $language)
    {
        $setting = Setting::where('key', $settingsKey)->firstOrFail();
        $value =  $setting->value;
        if($setting->is_translatable){
            $value = unserialize($value)[$language->code];
        }
        if(empty($value)){
            return '';
        }

        return '/' . $value;
    }
}
