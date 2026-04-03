<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Http\Resources\ArticleResource;
use App\Modules\Articles\Http\Resources\CategoryResource;
use App\Http\Resources\CommentableResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\RedirectResource;
use App\Http\Resources\SettingResource;
use App\Modules\Articles\Http\Resources\TagResource;
use App\Http\Resources\TranslationTextResource;
use App\Modules\Users\Http\Resources\UserResource;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\Category;
use App\Models\Commentable;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Redirect;
use App\Models\Setting;
use App\Modules\Articles\Models\Tag;
use App\Models\TranslationText;
use App\Modules\Books\Http\Resources\BookAuthorResource;
use App\Modules\Books\Http\Resources\BookGenreResource;
use App\Modules\Books\Http\Resources\BookResource;
use App\Modules\Books\Models\Book;
use App\Modules\Books\Models\BookAuthor;
use App\Modules\Books\Models\BookGenre;
use App\Modules\News\Http\Resources\NewsCategoryResource;
use App\Modules\News\Http\Resources\NewsResource;
use App\Modules\News\Models\News;
use App\Modules\News\Models\NewsCategory;
use App\Modules\News\Models\NewsTag;
use App\Modules\Products\Http\Resources\ProductResource;
use App\Modules\Products\Models\Product;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Repositories\LanguageRepository;
use App\Repositories\SettingRepository;
use Yajra\DataTables\Facades\DataTables;

class ContentController extends Controller
{

    public function fetchMenu()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }

        $menu = Menu::with('children')->where('parent_id', null)->orderBy('order')->get();

        return response()->json(['data' => MenuResource::collection($menu)]);
    }

    public function fetchLanguages()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }

        $languages = Language::all();

        return response()->json(['data' => LanguageResource::collection($languages)]);
    }

    public function fetchSettings()
    {
        $settings = Setting::all();

        return response()->json(['data' => SettingResource::collection($settings)]);
    }

    public function fetchTranslations()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }

        $translations = TranslationText::all();

        return response()->json(['data' => TranslationTextResource::collection($translations)]);
    }

    public function fetchContent()
    {
        $path = urldecode(request()->query('path'));
        if (empty($path)) {
            return response()->json(["status" => "error", "message" => "Missing \"path\" arguments"], 400);
        }
        if (!str_starts_with($path, '/')) {
            return response()->json(["status" => "error", "message" => "\"path\" should start with '/'"], 400);
        }
        $lang = request()->query('lang');
        $languageRepository = app(LanguageRepository::class);
        $languages = $languageRepository->all();
        if ($languages->count() < 2) {
            // site is not multilingual (test it later)
            $lang = Language::pluck('code')->first();
            app()->setLocale($lang);
        } else {
            // site is multilingual
            if (empty($lang)) {
                // we have to find the language
                if (strlen($path) === 1) { // $path='/'
                    $lang = Language::where('default', true)->pluck('code')->first();
                } else if (strlen($path) === 2) {
                    return response()->json(["status" => "error", "message" => "\"path\" should has at least 4 characters like: \"/en/\""], 400);
                } else if (strlen($path) === 3) { // $path = /en
                    $lang = substr($path, 1);
                    $path = '/';
                } else if (strlen($path) > 3) { // $path = /en/ or $path = /en/something
                    $lang = substr($path, 1, 2);
                    $path = substr($path, 3);
                }
            }
            $langIsValid = false;
            foreach ($languages as $language) {
                if ($lang === $language->code) {
                    $langIsValid = true;
                    break;
                }
            }
            if (!$langIsValid) {
                $path = request()->path;
                // $lang = Language::where('default', true)->pluck('code')->first();

                return response()->json(["status" => "error", "message" => "Can not detect the language. " . $lang], 400);
            }
            app()->setLocale($lang);
        }
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        $settings = Setting::all();
        $translationTexts = TranslationText::all();
        $menus = Menu::with('children')->where('parent_id', null)->orderBy('order')->get();
        // TODO: add books and book genres to the response
        $responseData = [
            'page' => collect(),
            'article' => collect(),
            'category' => collect(),
            'tag' => collect(),
            'news' => collect(),
            'article_prefix' => '',
            'product' => collect(),
            'product_category' => collect(),
            'product_prefix' => '',
            'book' => collect(),
            'book_genre' => collect(),
            'book_prefix' => '',
            'auth' => collect(),
            'redirect' => collect(),
            'notfound' => false,
            'path' => $path,
            'lang' => $lang,
            'content_type' => '',
        ];

        if (auth()->check()) {
            $responseData["auth"] = UserResource::make(auth()->user());
        }

        $responseCode = 200;
        $parsedUrl = parse_url($path);
        $path = $parsedUrl['path'];
        // $query = $parsedUrl['query']; if isset($parsedUrl['query']) // not needed yet

        $page = Page::withAllWidgetData()->where('slug->' . app()->getLocale(), $path)->where('status', 'published')->first();
        if ($page) {
            $responseData["page"] = PageResource::make($page);
            $responseData['content_type'] = 'page';

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Category
        $category = Category::with(['children', 'parent'])->where('slug->' . app()->getLocale(), $path)->where('status', 'published')->first(); //with(['articles' => fn($query) => $query->limit(10)])->
        if ($category) {
            $responseData["category"] = CategoryResource::make($category);
            $responseData['content_type'] = 'category';

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Tag
        $tag = Tag::where('name->' . app()->getLocale(), $path)->first(); // with(['articles' => fn($query) => $query->limit(10)])->
        if ($tag) {
            $responseData["tag"] = TagResource::make($tag);
            $responseData['content_type'] = 'tag';

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Article
        //can be empty or 'articles' can be change depends on your need some websites like to have /articles before the slug of each article
        $articlePrefixSetting = $settings->where('key', 'article-prefix')->first();
        $articlePath = $path;
        $articlePrefix = '';
        // $articlePrefixSetting->value is "articles" by default
        $settingsValue = $articlePrefixSetting->value;
        if ($articlePrefixSetting->is_translatable) {
            $settingsValue = unserialize($settingsValue)[$lang];
        }
        if (!empty($articlePrefixSetting) && !empty($settingsValue)) {
            $articlePrefix = '/' . trim($settingsValue, '/');
            $articlePath = substr($path, strlen($articlePrefix));
        }
        $article = Article::withAllWidgetData()
            ->with(['category', 'tags'])
            ->where('slug->' . app()->getLocale(), $articlePath)
            ->where('status', 'published')
            ->first();
        if ($article) {
            // here check if category is correct do it, otherwise return 404
            $responseData["article_prefix"] = $articlePrefix;
            $responseData["article"] = ArticleResource::make($article)->additional([
                'article_prefix' => $articlePrefix
            ]);
            $responseData['content_type'] = 'article';
            $article->increment('views');

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Product
        $productPrefixSetting = $settings->where('key', 'product-prefix')->first();
        $productPath = $path;
        $productPrefix = '';
        // $productPrefixSetting->value is "products" by default
        $settingsValue = $productPrefixSetting->value;
        if ($productPrefixSetting->is_translatable) {
            $settingsValue = unserialize($settingsValue)[$lang];
        }
        if (!empty($productPrefixSetting) && !empty($settingsValue)) {
            $productPrefix = '/' . trim($settingsValue, '/');
            $productPath = substr($path, strlen($productPrefix));
        }
        $product = Product::withAllWidgetData()
            ->with(['category'])
            ->where('slug->' . app()->getLocale(), $productPath)
            ->where('status', 'published')
            ->first();
        if ($product) {
            // here check if productCategory is correct do it
            $responseData["product_prefix"] = $productPrefix;
            $responseData["product"] = ProductResource::make($product)->additional([
                'product_prefix' => $productPrefix
            ]);
            $responseData['content_type'] = 'product';
            $product->increment('views');

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Book
        $bookPrefixSetting = $settings->where('key', 'book-prefix')->first();
        $bookPath = $path;
        $bookPrefix = '';
        // $bookPrefixSetting->value is "books" by default
        $settingsValue = $bookPrefixSetting->value;
        if ($bookPrefixSetting->is_translatable) {
            $settingsValue = unserialize($settingsValue)[$lang];
        }
        if (!empty($bookPrefixSetting) && !empty($settingsValue)) {
            $bookPrefix = '/' . trim($settingsValue, '/');
            $bookPath = substr($path, strlen($bookPrefix));
        }
        $book = Book::withAllWidgetData()
            ->with(['bookGenre'])
            ->where('slug->' . app()->getLocale(), $bookPath)
            ->where('status', 'published')
            ->first();
        if ($book) {
            // here check if bookGenre is correct do it
            $responseData["book_prefix"] = $bookPrefix;
            $responseData["book"] = BookResource::make($book)->additional([
                'book_prefix' => $bookPrefix
            ]);
            $responseData['content_type'] = 'book';
            $book->increment('views');

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is News
        $newsPrefixSetting = $settings->where('key', 'news-prefix')->first();
        $newsPath = $path;
        $newsPrefix = '';
        // $newsPrefixSetting->value is "news" by default
        $settingsValue = $newsPrefixSetting->value;
        if ($newsPrefixSetting->is_translatable) {
            $settingsValue = unserialize($settingsValue)[$lang];
        }
        if (!empty($newsPrefixSetting) && !empty($settingsValue)) {
            $newsPrefix = '/' . trim($settingsValue, '/');
            $newsPath = substr($path, strlen($newsPrefix));
        }
        $news = News::withAllWidgetData()
            ->with(['category', 'tags', 'author'])
            ->where('slug->' . app()->getLocale(), $newsPath)
            ->where('status', 'published')
            ->first();
        if ($news) {
            // here check if bookGenre is correct do it
            $responseData["news_prefix"] = $newsPrefix;
            $responseData["news"] = NewsResource::make($news)->additional([
                'news_prefix' => $newsPrefix
            ]);
            $responseData['content_type'] = 'news';
            $news->increment('views');

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Redirect
        $redirect = Redirect::where('from', $path)->where('language', $lang)->orderBy('id', 'desc')->first();
        if ($redirect) {
            $responseData["redirect"] = RedirectResource::make($redirect);
            $responseData['content_type'] = 'redirect';

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is 404
        $responseCode = 404;
        $responseData["notfound"] = true;
        $responseData['content_type'] = 'notfound';

        // $responseData["debuger"] = debugbar()->getData();
        return response()->json(['data' => $responseData], $responseCode);
    }

    public function fetchArticles()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }

        $categorySlug = request()->query('category') ?? "";
        $tagName      = request()->query('tag') ?? "";
        $authorId     = request()->query('author');
        $sort         = request()->query('sort');
        $start        = (int) request()->get('start', 0);
        $length       = (int) request()->get('length', 12);

        if ($length > 24) {
            $length = 24;
        }

        $query = Article::with(['author', 'category', 'tags'])
            ->where('status', 'published')
            ->whereNotNull('title->' . app()->getLocale());

        if (!empty($sort)) {
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');        // was 'desc' — fixed
            } elseif ($sort === 'views') {
                $query->orderBy('views', 'desc');
            } elseif ($sort === 'random') {
                $query->inRandomOrder();
            } elseif ($sort === 'title') {
                $query->orderBy('title->' . app()->getLocale(), 'asc');
            } else {
                $query->orderBy('created_at', 'desc');       // was 'asc' — fixed
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (!empty($authorId)) {
            $query->where('author_id', $authorId);
        }

        // Filter by category
        if (!empty($categorySlug) && $categorySlug !== 'null') {
            $category = Category::where('slug->' . app()->getLocale(), '/' . ltrim($categorySlug, '/'))->first();
            if ($category) {
                $query->where('category_id', $category->id);  // was replacing $query entirely — fixed
            } else {
                $query->whereRaw('1 = 0');                    // was returning 404 — fixed
            }
        }

        // Filter by tag
        if (!empty($tagName) && $tagName !== 'null') {
            $tag = Tag::where('name->' . app()->getLocale(), $tagName)->first();
            if ($tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('tags.id', $tag->id);           // was replacing $query entirely — fixed
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Search filter
        if (request()->has('search') && !empty(request()->search)) {
            $searchValue = request()->search;
            $query->where(function ($q) use ($searchValue) {
                $q->whereRaw('LOWER(JSON_EXTRACT(title, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"'])
                    ->orWhereRaw('LOWER(JSON_EXTRACT(description, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"']);
            });
        }

        // Count BEFORE offset/limit
        $recordsTotal = $query->count();

        // Now paginate
        $articles = $query->offset($start)->limit($length)->get();

        $settingRepository = app(SettingRepository::class);
        $prefix = $settingRepository->findByKey(SettingKeyEnum::ARTICLE_PREFIX);

        return response()->json([
            'draw'            => (int) request()->get('draw', 0),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => ArticleResource::collection($articles),
            'prefix'          => $prefix,
        ]);
    }

    public function fetchBooks()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }

        $bookGenre = request()->query('book_genre') ?? "";
        $authorId  = request()->query('author');
        $sort      = request()->query('sort');
        $start     = (int) request()->get('start', 0);
        $length    = (int) request()->get('length', 24);

        if ($length > 24) {
            $length = 24;
        }

        $query = Book::with(['author', 'bookGenre'])
            ->where('status', 'published')
            ->whereNotNull('title->' . app()->getLocale());

        if (!empty($sort)) {
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'views') {
                $query->orderBy('views', 'desc');
            } elseif ($sort === 'random') {
                $query->inRandomOrder();
            } elseif ($sort === 'title') {
                $query->orderBy('title->' . app()->getLocale(), 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        if (!empty($authorId)) {
            $query->where('author_id', $authorId);
        }

        if (!empty($bookGenre) && $bookGenre !== 'null') {
            $genre = BookGenre::where('slug->' . app()->getLocale(), '/' . ltrim($bookGenre, '/'))->first(); // fixed: was ltrim($bookGenre), '/'
            if ($genre) {
                $query->where('book_genre_id', $genre->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Search filter
        if (request()->has('search') && !empty(request()->search)) {
            $searchValue = request()->search;
            $query->where(function ($q) use ($searchValue) {
                $q->whereRaw('LOWER(JSON_EXTRACT(title, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"'])
                    ->orWhereRaw('LOWER(JSON_EXTRACT(description, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"']);
            });
        }

        // Count BEFORE offset/limit
        $recordsTotal = $query->count();

        // Now paginate
        $books = $query->offset($start)->limit($length)->get();

        $settingRepository = app(SettingRepository::class);
        $prefix = $settingRepository->findByKey(SettingKeyEnum::BOOK_PREFIX);

        return response()->json([
            'draw'            => (int) request()->get('draw', 0),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => BookResource::collection($books),
            'prefix'          => $prefix,
        ]);
    }

    public function fetchNews()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }
        $newsCategory = request()->query('c') ?? "";
        $newsTag = request()->query('t') ?? "";
        $authorId     = request()->query('author');
        $sort         = request()->query('sort');
        $start        = (int) request()->get('start', 0);
        $length       = (int) request()->get('length', 12);

        if ($length > 24) {
            $length = 24;
        }

        $query = News::with(['author', 'category'])
            ->where('status', 'published')
            ->whereNotNull('title->' . app()->getLocale());

        if (!empty($sort)) {
            if ($sort === 'oldest') {
                $query->orderBy('news_date', 'asc');
            } elseif ($sort === 'views') {
                $query->orderBy('views', 'desc');
            } elseif ($sort === 'random') {
                $query->inRandomOrder();
            } elseif ($sort === 'title') {
                $query->orderBy('title->' . app()->getLocale(), 'asc');
            } else {
                $query->orderBy('news_date', 'desc');
            }
        } else {
            $query->orderBy('news_date', 'desc');
        }

        if (!empty($authorId)) {
            $query->where('author_id', $authorId);
        }

        if (!empty($newsCategory) && $newsCategory !== 'null') {
            $category = NewsCategory::where('slug->' . app()->getLocale(), '/' . ltrim($newsCategory, '/'))->first();
            if ($category) {
                $query->where('news_category_id', $category->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if (!empty($newsTag) && $newsTag !== 'null') {
            $tag = NewsTag::where('slug->' . app()->getLocale(), ltrim($newsTag, '/'))->first();
            if ($tag) {
                $query->whereHas('tags', function ($q) use ($tag) {
                    $q->where('news_tags.id', $tag->id);
                });
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        // Search filter
        if (request()->has('search') && !empty(request()->search)) {
            $searchValue = request()->search;
            $query->where(function ($q) use ($searchValue) {
                $q->whereRaw('LOWER(JSON_EXTRACT(title, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"'])
                    ->orWhereRaw('LOWER(JSON_EXTRACT(description, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"']);
            });
        }

        // Count BEFORE offset/limit
        $recordsTotal = $query->count();

        // Now paginate
        $news = $query->offset($start)->limit($length)->get();

        $settingRepository = app(SettingRepository::class);
        $prefix = $settingRepository->findByKey(SettingKeyEnum::NEWS_PREFIX);

        return response()->json([
            'draw'            => (int) request()->get('draw', 0),
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => NewsResource::collection($news),
            'prefix'          => $prefix,
        ]);
    }

    public function fetchAuthors()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }

        $authors = BookAuthor::withCount('books')->whereNotNull('name->' . app()->getLocale())->limit(20)->get();

        return response()->json([
            'data' => BookAuthorResource::collection($authors)
        ]);
    }

    public function fetchBookGenres()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }
        $bookGenres = BookGenre::where(function ($query) {
            $query->whereNull('hide_from_frontend')
                ->orWhere('hide_from_frontend', false);
        })->withCount('books')->get();

        return response()->json([
            'data' => BookGenreResource::collection($bookGenres)
        ]);
    }

    public function fetchNewsCategories()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }
        $newsCategories = NewsCategory::where(function ($query) {
            $query->whereNull('hide_from_frontend')
                ->orWhere('hide_from_frontend', false);
        })->withCount('news')->get();

        return response()->json([
            'data' => NewsCategoryResource::collection($newsCategories)
        ]);
    }

    public function fetchCategories()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }

        $categoriesQuery = Category::query();

        return DataTables::of($categoriesQuery)
            ->editColumn('name', function ($category) {
                return $category->name;
            })
            ->editColumn('slug', function ($category) {
                return $category->slug;
            })
            ->make(true);
    }

    public function fetchBookComments()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }
        $start = request()->get('start', 0);
        $length = request()->get('length', 24);
        if ($length > 24) {
            $length = 24;
        }

        $bookSlug = request()->query('book_slug');

        $book = Book::withAllWidgetData()
            ->with(['bookGenre'])
            ->where('slug->' . app()->getLocale(), $bookSlug)
            ->where('status', 'published')
            ->first();

        if ($book === null) {
            return response()->json([
                'data' => [],
                'message' => 'Book not found'
            ], 404);
        }

        $comments = Commentable::where('commentable_type', 'App\Modules\Books\Models\Book')
            ->where('commentable_id', $book->id)
            ->where('content->' . app()->getLocale(), '!=', null)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'data' => CommentableResource::collection($comments)
        ]);
    }

    public function storeBookComments()
    {
        $name = request()->name;
        $email = request()->email;
        $content = request()->content;
        $stars = request()->stars;
        $lang = request()->lang;
        $bookSlug = request()->book_slug;

        if (!$bookSlug || !$lang || !$content || !$name || !$email) {
            return response()->json(['error' => 'Invalid inputs'], 400);
        }

        if (strlen($lang) === 2) {
            app()->setLocale($lang);
        } else {
            return response()->json(['error' => 'Language not specified'], 400);
        }

        $book = Book::withAllWidgetData()
            ->with(['bookGenre'])
            ->where('slug->' . app()->getLocale(), $bookSlug)
            ->where('status', 'published')
            ->first();

        if (!$book) {
            return response()->json([
                'data' => [],
                'message' => 'Book not found'
            ], 404);
        }

        $comment = new Commentable();
        $comment->commentable_type = 'App\Modules\Books\Models\Book';
        $comment->commentable_id = $book->id;
        $comment->setTranslation('content', app()->getLocale(), $content);
        $comment->name = $name;
        $comment->email = $email;
        $comment->stars = $stars;
        $comment->save();

        $totalStars = ($book->stars * $book->total_votes) + $stars;
        $book->total_votes = $book->total_votes + 1;
        $book->stars = $totalStars / $book->total_votes;
        $book->save();

        return response()->json(['message' => 'Comment saved successfully'], 201);
    }

    public function fetchNewsComments()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }
        $start = request()->get('start', 0);
        $length = request()->get('length', 24);
        if ($length > 24) {
            $length = 24;
        }

        $newsSlug = request()->query('news_slug');

        $newsSlug = urldecode($newsSlug);

        $news = News::where('slug->' . app()->getLocale(), $newsSlug)
            ->where('status', 'published')
            ->first();

        if ($news === null) {
            return response()->json([
                'data' => [],
                'message' => 'News not found'
            ], 404);
        }

        $comments = Commentable::where('commentable_type', 'App\Modules\News\Models\News')
            ->where('commentable_id', $news->id)
            ->where('content->' . app()->getLocale(), '!=', null)
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc')
            ->offset($start)
            ->limit($length)
            ->get();

        return response()->json([
            'data' => CommentableResource::collection($comments)
        ]);
    }

    public function storeNewsComments()
    {
        $name = request()->name;
        $email = request()->email;
        $content = request()->content;
        $stars = request()->stars;
        $lang = request()->lang;
        $newsSlug = request()->news_slug;

        if (!$newsSlug || !$lang || !$content || !$name || !$email) {
            return response()->json(['error' => 'Invalid inputs'], 400);
        }

        if (strlen($lang) === 2) {
            app()->setLocale($lang);
        } else {
            return response()->json(['error' => 'Language not specified'], 400);
        }

        // check later why it's needed:
        $newsSlug = urldecode($newsSlug);

        $news = News::where('slug->' . app()->getLocale(), $newsSlug)->first();

        if (!$news) {
            return response()->json([
                'data' => [],
                'message' => 'News not found'
            ], 404);
        }

        $comment = new Commentable();
        $comment->commentable_type = 'App\Modules\News\Models\News';
        $comment->commentable_id = $news->id;
        $comment->setTranslation('content', app()->getLocale(), $content);
        $comment->name = $name;
        $comment->email = $email;
        $comment->stars = $stars;
        $comment->save();

        return response()->json(['message' => 'Comment saved successfully'], 201);
    }
}
