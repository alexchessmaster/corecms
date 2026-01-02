<?php

namespace App\Enums;

enum SettingKeyEnum: string
{
    case ARTICLE_PREFIX = 'article-prefix';
    case PRODUCT_PREFIX = 'product-prefix';
    case BOOK_PREFIX = 'book-prefix';

    case DEFAULT_USER_ROLE = 'default-user-role';

    case ARTICLE_PATH_HIERARCHICAL = 'article-path-hierarchical';
    case BOOK_PATH_HIERARCHICAL = 'book-path-hierarchical';
    case PRODUCT_PATH_HIERARCHICAL = 'product-path-hierarchical';

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
    case DEFAULT_SITEMAP_PRIORITY_PAGES = 'default-sitemap-priority-pages';
    case DEFAULT_SITEMAP_PRIORITY_ARTICLES = 'default-sitemap-priority-articles';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
