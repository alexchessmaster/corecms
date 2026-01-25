<?php

namespace App\Modules\Shared\Enums;

enum RedirectTypeEnum: string
{
    case MANUAL = 'manual';

    // Article Category
    case ARTICLE_CATEGORY_CREATED = 'article_category_created';
    case ARTICLE_CATEGORY_UPDATED = 'article_category_updated';
    case ARTICLE_CATEGORY_DELETED = 'article_category_deleted';
    // Article Tag
    case ARTICLE_TAG_CREATED = 'article_tag_created';
    case ARTICLE_TAG_UPDATED = 'article_tag_updated';
    case ARTICLE_TAG_DELETED = 'article_tag_deleted';
    // Article
    case ARTICLE_CREATED = 'article_created';
    case ARTICLE_UPDATED = 'article_updated';
    case ARTICLE_DELETED = 'article_deleted';

    // Book Genre
    case BOOK_GENRE_CREATED = 'book_genre_created';
    case BOOK_GENRE_UPDATED = 'book_genre_updated';
    case BOOK_GENRE_DELETED = 'book_genre_deleted';
    // Book Tag
    case BOOK_TAG_CREATED = 'book_tag_created';
    case BOOK_TAG_UPDATED = 'book_tag_updated';
    case BOOK_TAG_DELETED = 'book_tag_deleted';
    // Book
    case BOOK_CREATED = 'book_created';
    case BOOK_UPDATED = 'book_updated';
    case BOOK_DELETED = 'book_deleted';

    // Product Category
    case PRODUCT_CATEGORY_CREATED = 'product_category_created';
    case PRODUCT_CATEGORY_UPDATED = 'product_category_updated';
    case PRODUCT_CATEGORY_DELETED = 'product_category_deleted';
    // Product Tag
    case PRODUCT_TAG_CREATED = 'product_tag_created';
    case PRODUCT_TAG_UPDATED = 'product_tag_updated';
    case PRODUCT_TAG_DELETED = 'product_tag_deleted';
    // Product
    case PRODUCT_CREATED = 'product_created';
    case PRODUCT_UPDATED = 'product_updated';
    case PRODUCT_DELETED = 'product_deleted';

    // News Category
    case NEWS_CATEGORY_CREATED = 'news_category_created';
    case NEWS_CATEGORY_UPDATED = 'news_category_updated';
    case NEWS_CATEGORY_DELETED = 'news_category_deleted';
    // News Tag
    case NEWS_TAG_CREATED = 'news_tag_created';
    case NEWS_TAG_UPDATED = 'news_tag_updated';
    case NEWS_TAG_DELETED = 'news_tag_deleted';
    // News
    case NEWS_CREATED = 'news_created';
    case NEWS_UPDATED = 'news_updated';
    case NEWS_DELETED = 'news_deleted';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
