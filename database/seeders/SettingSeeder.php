<?php

namespace Database\Seeders;

use App\Modules\Settings\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // php artisan db:seed --class=SettingSeeder

        \DB::table('settings')->truncate();

        $settings = [
            [
                'key' => 'default-user-role',
                'value' => 'guest',
                'description' => 'Role automatically assigned to new users after registration.'
            ],

            [
                'key' => 'is-website-links-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-menu-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-page-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-article-active',
                'value' => 'false',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-book-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-product-active',
                'value' => 'false',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-news-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-comments-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-reservation-active',
                'value' => 'false',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-widgets-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-fields-active',
                'value' => 'false',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-translation-texts-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-language-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-users-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-redirect-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],
            [
                'key' => 'is-upload-active',
                'value' => 'true',
                'description' => "If it is 'true' it will be shown in the sidebar menu."
            ],

            [
                'key' => 'article-prefix',
                'value' => 'articles',
                'description' => 'Prefix for article URLs (e.g., /articles/article-path). Leave empty for no prefix.'
            ],
            [
                'key' => 'book-prefix',
                'value' => 'books',
                'description' => 'Prefix for book URLs (e.g., /books/book-path). Leave empty for no prefix.'
            ],
            [
                'key' => 'product-prefix',
                'value' => 'products',
                'description' => 'Prefix for product URLs (e.g., /products/product-path). Leave empty for no prefix.'
            ],
            [
                'key' => 'news-prefix',
                'value' => 'news',
                'description' => 'Prefix for news URLs (e.g., /news/news-path). Leave empty for no prefix.'
            ],

            [
                'key' => 'article-path-hierarchical',
                'value' => 'true',
                'description' => 'If true, article URLs include category hierarchy (e.g., /parent-category/category/article-path). If false, URLs are flat (e.g., /article-path).'
            ],
            [
                'key' => 'book-path-hierarchical',
                'value' => 'true',
                'description' => 'If true, book URLs include category hierarchy (e.g., /parent-category/category/book-path). If false, URLs are flat (e.g., /book-path).'
            ],
            [
                'key' => 'product-path-hierarchical',
                'value' => 'true',
                'description' => 'If true, product URLs include category hierarchy (e.g., /parent-category/category/product-path). If false, URLs are flat (e.g., /product-path).'
            ],
            [
                'key' => 'news-path-hierarchical',
                'value' => 'true',
                'description' => 'If true, news URLs include category hierarchy (e.g., /parent-category/category/news-path). If false, URLs are flat (e.g., /product-path).'
            ],

            [
                'key' => 'default-sitemap-change-frequency-pages',
                'value' => 'yearly',
                'description' => 'The default sitemap-change-frequency for pages'
            ],
            [
                'key' => 'default-sitemap-change-frequency-articles',
                'value' => 'yearly',
                'description' => 'The default sitemap-change-frequency for articles'
            ],
            [
                'key' => 'default-sitemap-change-frequency-books',
                'value' => 'yearly',
                'description' => 'The default sitemap-change-frequency for books'
            ],
            [
                'key' => 'default-sitemap-change-frequency-products',
                'value' => 'yearly',
                'description' => 'The default sitemap-change-frequency for products'
            ],
            [
                'key' => 'default-sitemap-change-frequency-news',
                'value' => 'yearly',
                'description' => 'The default sitemap-change-frequency for news'
            ],
            [
                'key' => 'default-sitemap-priority-pages',
                'value' => '0.5',
                'description' => 'The default sitemap-change-priority for pages'
            ],
            [
                'key' => 'default-sitemap-priority-articles',
                'value' => '0.8',
                'description' => 'The default sitemap-change-priority for articles'
            ],
            [
                'key' => 'default-sitemap-priority-books',
                'value' => '0.8',
                'description' => 'The default sitemap-change-priority for books'
            ],
            [
                'key' => 'default-sitemap-priority-products',
                'value' => '0.8',
                'description' => 'The default sitemap-change-priority for products'
            ],
            [
                'key' => 'default-sitemap-priority-news',
                'value' => '0.8',
                'description' => 'The default sitemap-change-priority for news'
            ],


            [
                'key' => 'notification-email-enabled',
                'value' => 'false',
                'description' => 'If true, you will receive email notifications; if false, you will not.'
            ],
            [
                'key' => 'notification-slack-enabled',
                'value' => 'false',
                'description' => 'If true, you will receive Slack notifications; if false, you will not.'
            ],
            [
                'key' => 'notification-email-recipients',
                'value' => '',
                'description' => 'Enter the email addresses to receive notifications, separated by commas. Leave empty to disable.'
            ],
            [
                'key' => 'notification-slack-webhook',
                'value' => 'false',
                'description' => 'Enter the the slack webhook URL. Leave empty to disable.'
            ],
            [
                'key' => 'notification-on-contact-form',
                'value' => '',
                'description' => 'Receive a notification when someone submits the contact us form.'
            ],
            [
                'key' => 'notification-on-reservation',
                'value' => '',
                'description' => 'Receive a notification when someone makes a reservation.'
            ],
            [
                'key' => 'notification-on-payment',
                'value' => '',
                'description' => 'Receive a notification when someone makes a payment.'
            ],
            [
                'key' => 'notification-on-user-registration',
                'value' => '',
                'description' => 'Receive a notification when a new user registers.'
            ],
        ];

        foreach ($settings as $setting) {
            if(!Setting::where('key', $setting['key'])->exists()) {
                Setting::create([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'description' => $setting['description']
                ]);
            }
        }
    }
}
