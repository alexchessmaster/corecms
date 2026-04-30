<?php

namespace App\Console\Commands;

use App\Modules\Settings\Models\Setting;
use App\Modules\Languages\Models\Language;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Modules\Settings\Repositories\SettingRepository;
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
        $languages = Language::all();
        $settings = Setting::get()->keyBy('key');

        $settingRepository = app(SettingRepository::class);

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
            $lang = $language->code;
            $sitemap = Sitemap::create();

            foreach ($tables as $table) {
                $pages = $this->getPagesOrArticlesForLanguage($table, $lang);

                foreach ($pages as $page) {
                    if (array_key_exists('slug', $page)) {
                        $url = Url::create(UrlHelper::getFullUrlBySlug($page['slug'], $table, '', $language->code));

                        foreach ($page['alternates'] as $altLang => $altSlug) {
                            $url->addAlternate(UrlHelper::getFullUrlBySlug($altSlug, $table, '', $altLang), $altLang);
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

            $this->generateNewsSitemap($language, $settings, $lang);
        }

        $this->generateSitemapIndex($languages, $frontendBaseUrl);

        return 0;
    }

    /**
     * Generate a Google News-compliant sitemap for a given language.
     * Only includes news published within the last 48 hours (Google's requirement).
     */
    private function generateNewsSitemap($language, $settings, $lang)
    {
        // 48 hours to keep the news in the news sitemap. The news should be for ever in the normal sitemap as well
        $cutoff = Carbon::now()->subHours(48);

        $newsItems = \DB::table('news')
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('sitemap_exclude')
                    ->orWhere('sitemap_exclude', false);
            })
            ->where('created_at', '>=', $cutoff)
            ->get(['id', 'slug', 'title', 'created_at', 'updated_at', 'sitemap_priority', 'sitemap_change_frequency']);

        $rootXml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:news="http://www.google.com/schemas/sitemap-news/0.9"></urlset>';
        $xml = new \SimpleXMLElement($rootXml);

        foreach ($newsItems as $newsItem) {
            $slugs = json_decode($newsItem->slug, true);

            if (!is_array($slugs) || !array_key_exists($lang, $slugs) || empty($slugs[$lang])) {
                continue;
            }

            $fullUrl = UrlHelper::getFullUrlBySlug($slugs[$lang], 'news', '', $lang);

            $titles = json_decode($newsItem->title, true);
            $title = is_array($titles)
                ? ($titles[$lang] ?? reset($titles))
                : $newsItem->title;

            $urlNode = $xml->addChild('url');
            $urlNode->addChild('loc', htmlspecialchars($fullUrl));
            $urlNode->addChild('lastmod', Carbon::createFromFormat('Y-m-d H:i:s', $newsItem->updated_at)->toAtomString());

            $newsNode = $urlNode->addChild('news:news', '', 'http://www.google.com/schemas/sitemap-news/0.9');
            $publicationNode = $newsNode->addChild('news:publication', '', 'http://www.google.com/schemas/sitemap-news/0.9');
            $publicationNode->addChild('news:name', ltrim(htmlspecialchars($language->domain), 'https://'), 'http://www.google.com/schemas/sitemap-news/0.9');
            $publicationNode->addChild('news:language', $lang, 'http://www.google.com/schemas/sitemap-news/0.9');
            $newsNode->addChild('news:publication_date', Carbon::createFromFormat('Y-m-d H:i:s', $newsItem->created_at)->toAtomString(), 'http://www.google.com/schemas/sitemap-news/0.9');
            $newsNode->addChild('news:title', htmlspecialchars($title), 'http://www.google.com/schemas/sitemap-news/0.9');
        }

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;

        $xmlContents = $xml->asXML();
        if ($xmlContents === false || !$dom->loadXML($xmlContents)) {
            $dom->loadXML($rootXml);
        }

        $newsPath = public_path("sitemap-news-{$lang}.xml");
        $dom->save($newsPath);
        $this->info("Generated news sitemap for {$lang}: {$newsPath}");
    }

    private function getPagesOrArticlesForLanguage($table, $lang)
    {
        return \DB::table($table)
            ->where('status', 'published')
            ->where(function ($query) {
                $query->whereNull('sitemap_exclude')
                    ->orWhere('sitemap_exclude', false);
            })
            ->get(['id', 'slug', 'sitemap_priority', 'sitemap_change_frequency', 'updated_at'])
            ->map(function ($pageOrArticle) use ($lang) {
                $slugs = json_decode($pageOrArticle->slug, true);
                if (is_array($slugs) && array_key_exists($lang, $slugs)) {
                    return [
                        'slug' => $slugs[$lang],
                        'alternates' => collect($slugs)->filter(),
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
            $sitemapIndex->add("{$language->domain}/sitemap-{$language->code}.xml");
            $sitemapIndex->add("{$language->domain}/sitemap-news-{$language->code}.xml");
        }

        $sitemapIndexPath = public_path('sitemap.xml');
        $sitemapIndex->writeToFile($sitemapIndexPath);
        $this->info("Generated sitemap index: {$sitemapIndexPath}");
    }

    private function getPrefixSettingsValue($settingsKey, $language)
    {
        $setting = Setting::where('key', $settingsKey)->firstOrFail();
        $value = $setting->value;
        if ($setting->is_translatable) {
            $value = unserialize($value)[$language->code];
        }
        if (empty($value)) {
            return '';
        }

        return '/' . $value;
    }
}
