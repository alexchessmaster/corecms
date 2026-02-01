<?php

namespace App\Modules\Shared\Enums;

enum SettingKeyEnum: string
{
    case DEFAULT_USER_ROLE = 'default-user-role';

    case IS_WEBSITE_LINKS_ACTIVE = 'is-website-links-active';
    case IS_MENU_ACTIVE = 'is-menu-active';
    case IS_PAGE_ACTIVE = 'is-page-active';
    case IS_ARTICLE_ACTIVE = 'is-article-active';
    case IS_BOOK_ACTIVE = 'is-book-active';
    case IS_PRODUCT_ACTIVE = 'is-product-active';
    case IS_NEWS_ACTIVE = 'is-news-active';
    case IS_COMMENTS_ACTIVE = 'is-comments-active';
    case IS_RESERVATION_ACTIVE = 'is-reservation-active';
    case IS_WIDGETS_ACTIVE = 'is-widgets-active';
    case IS_FIELDS_ACTIVE = 'is-fields-active';
    case IS_TRANSLATION_TEXTS_ACTIVE = 'is-translation-texts-active';
    case IS_LANGUAGE_ACTIVE = 'is-language-active';
    case IS_USERS_ACTIVE = 'is-users-active';
    case IS_REDIRECT_ACTIVE = 'is-redirect-active';
    case IS_UPLOAD_ACTIVE = 'is-upload-active';

    case ARTICLE_PREFIX = 'article-prefix';
    case BOOK_PREFIX = 'book-prefix';
    case PRODUCT_PREFIX = 'product-prefix';
    case NEWS_PREFIX = 'news-prefix';

    case ARTICLE_PATH_HIERARCHICAL = 'article-path-hierarchical';
    case BOOK_PATH_HIERARCHICAL = 'book-path-hierarchical';
    case PRODUCT_PATH_HIERARCHICAL = 'product-path-hierarchical';
    case NEWS_PATH_HIERARCHICAL = 'news-path-hierarchical';

    case NOTIFICATION_EMAIL_ENABLED = 'notification-email-enabled';
    case NOTIFICATION_SLACK_ENABLED = 'notification-slack-enabled';
    case NOTIFICATION_EMAIL_RECIPIENTS = 'notification-email-recipients';
    case NOTIFICATION_SLACK_WEBHOOK = 'notification-slack-webhook';
    case NOTIFICATION_ON_CONTACT_FORM = 'notification-on-contact-form';
    case NOTIFICATION_ON_RESERVATION = 'notification-on-reservation';
    case NOTIFICATION_ON_PAYMENT = 'notification-on-payment';
    case NOTIFICATION_ON_USER_REGISTRATION = 'notification-on-user-registration';

    case DEFAULT_SITEMAP_CHANGE_FREQUENCY_PAGES = 'default-sitemap-change-frequency-pages';
    case DEFAULT_SITEMAP_CHANGE_FREQUENCY_ARTICLES = 'default-sitemap-change-frequency-articles';
    case DEFAULT_SITEMAP_CHANGE_FREQUENCY_BOOKS = 'default-sitemap-change-frequency-books';
    case DEFAULT_SITEMAP_CHANGE_FREQUENCY_PRODUCTS = 'default-sitemap-change-frequency-products';
    case DEFAULT_SITEMAP_CHANGE_FREQUENCY_NEWS = 'default-sitemap-change-frequency-news';
    case DEFAULT_SITEMAP_PRIORITY_PAGES = 'default-sitemap-priority-pages';
    case DEFAULT_SITEMAP_PRIORITY_ARTICLES = 'default-sitemap-priority-articles';
    case DEFAULT_SITEMAP_PRIORITY_BOOKS = 'default-sitemap-priority-books';
    case DEFAULT_SITEMAP_PRIORITY_PRODUCTS = 'default-sitemap-priority-products';
    case DEFAULT_SITEMAP_PRIORITY_NEWS = 'default-sitemap-priority-news';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
