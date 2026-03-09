<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CommentableResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\RedirectResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\TagResource;
use App\Http\Resources\TranslationTextResource;
use App\Http\Resources\UserResource;
use App\Models\Article;
use App\Models\Category;
use App\Models\Commentable;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Redirect;
use App\Models\Setting;
use App\Models\Tag;
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
use App\Modules\Products\Http\Resources\ProductResource;
use App\Modules\Products\Models\Product;
use App\Modules\Shared\Enums\SettingKeyEnum;
use App\Modules\Shared\Helpers\FileHelper;
use App\Modules\Shared\Helpers\TranslationHelper;
use App\Modules\Shared\Helpers\UrlHelper;
use App\Repositories\LanguageRepository;
use App\Stores\SettingStore;
use Illuminate\Support\Str;
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
        $languageRepository = new LanguageRepository;
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

        // Is Page
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
        if($articlePrefixSetting->is_translatable){
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

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Product
        $productPrefixSetting = $settings->where('key', 'product-prefix')->first();
        $productPath = $path;
        $productPrefix = '';
        // $productPrefixSetting->value is "products" by default
        $settingsValue = $productPrefixSetting->value;
        if($productPrefixSetting->is_translatable){
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

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Book
        $bookPrefixSetting = $settings->where('key', 'book-prefix')->first();
        $bookPath = $path;
        $bookPrefix = '';
        // $bookPrefixSetting->value is "books" by default
        $settingsValue = $bookPrefixSetting->value;
        if($bookPrefixSetting->is_translatable){
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

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is News
        $newsPrefixSetting = $settings->where('key', 'news-prefix')->first();
        $newsPath = $path;
        $newsPrefix = '';
        // $newsPrefixSetting->value is "news" by default
        $settingsValue = $newsPrefixSetting->value;
        if($newsPrefixSetting->is_translatable){
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
        // info($language);
        if ($language) {
            app()->setLocale($language);
        }
        $category = request()->query('category') ?? "";
        $tag = request()->query('tag') ?? "";
        $sort = request()->query('sort');

        $query = Article::query();

        // Filter by category if provided
        if (!empty($category) && $category !== 'null') {
            $category = Category::where('slug->' . app()->getLocale(), '/' . Str::slug($category))->first();
            if ($category) {
                $query = $category->articles();
            } else {
                return response()->json(['status' => 'error', 'message' => 'Category does not exist: '], 404);
            }
        }

        // Filter by tag if provided
        if (!empty($tag)) {
            $tag = Tag::where('name->' . app()->getLocale(), $tag)->first();
            if ($tag) {
                $query = $tag->articles();
            }
        }

        if ($sort === 'oldest') {
            $query = $query->orderBy('created_at', 'desc');
        } else {
            $query = $query->orderBy('created_at', 'asc');
        }

        return DataTables::of($query)
            ->editColumn('title', function ($article) {
                return $article->getTranslation('title', app()->getLocale());
            })
            ->editColumn('slug', function ($article) {
                return $article->getTranslation('slug', app()->getLocale());
            })
            ->editColumn('description', function ($article) {
                return $article->getTranslation('description', app()->getLocale());
            })
            ->editColumn('content', function ($article) {
                return $article->getTranslation('content', app()->getLocale());
            })
            ->editColumn('image', function ($article) {
                return str_starts_with($article->image, 'http')
                    ? $article->image
                    : config('app.url') . '/' . ltrim($article->image, '/');
            })
            ->editColumn('full_url', function ($article) {
                return $article->full_url;
            })
            ->filter(function ($query) {
                if (request()->has('search') && !empty(request()->search['value'])) {
                    $searchValue = request()->search['value'];
                    $query->where(function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(JSON_EXTRACT(title, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"'])
                            ->orWhereRaw('LOWER(JSON_EXTRACT(content, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"']);
                    });
                }
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $orderColumn = request()->columns[request()->order[0]['column']]['data'];
                    $orderDirection = request()->order[0]['dir'];
                    $query->orderBy($orderColumn, $orderDirection);
                }
            })
            ->make(true);
    }

    public function fetchBooks()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }
        $bookGenre = request()->query('book_genre') ?? "";
        $authorId = request()->query('author');
        $sort = request()->query('sort');
        $start = request()->get('start', 0);
        $length = request()->get('length', 24);
        if ($length > 24) {
            $length = 24;
        }

        $query = Book::with(['author', 'bookGenre'])
            ->where('status', 'published')->whereNotNull('title->' . app()->getLocale())
            ->offset($start)
            ->limit($length);

        if (! empty($sort)) {
            if ($sort === 'oldest') {
                $query = $query->orderBy('created_at', 'desc');
            } else if ($sort === 'views') {
                $query = $query->orderBy('views', 'desc');
            } else if ($sort === 'random') {
                $query = $query->inRandomOrder();
            } else if ($sort === 'title') {
                $query = $query->orderBy('title->' . app()->getLocale(), 'asc');
            } else { // 'created_at'
                $query = $query->orderBy('created_at', 'asc');
            }
        }

        if (! empty($authorId)) {
            $query = $query->where('author_id', $authorId);
        }

        // Filter by book genre if provided
        if (!empty($bookGenre) && $bookGenre !== 'null') {
            $bookGenre = BookGenre::where('slug->' . app()->getLocale(), '/' . ltrim($bookGenre), '/')->first();
            if ($bookGenre) {
                $query = $query->where('book_genre_id', $bookGenre->id);
            } else {
                $query = $query->whereRaw('1 = 0');
            }
        }

        return DataTables::of($query)
            ->editColumn('title', function ($book) {
                return $book->getTranslation('title', app()->getLocale());
            })
            ->editColumn('slug', function ($book) {
                return $book->getTranslation('slug', app()->getLocale());
            })
            ->editColumn('description', function ($book) {
                return $book->getTranslation('description', app()->getLocale());
            })
            ->editColumn('image', function ($book) {
                return FileHelper::addDomainPrefixIfValueIsAFile($book->image);
            })
            ->editColumn('book_genre', function ($book) {
                return $book?->bookGenre?->getTranslation('name', app()->getLocale()) ?? null;
            })
            ->addColumn('book_genre_slug', function ($book) {
                return $book?->bookGenre?->getTranslation('slug', app()->getLocale()) ?? null;
            })
            ->addColumn('author_name', function ($book) {
                return $book?->author?->getTranslation('name', app()->getLocale()) ?? null;
            })
            ->editColumn('full_url', function ($book) {
                return $book->full_url;
            })
            ->filter(function ($query) {
                if (request()->has('search') && !empty(request()->search)) {
                    $searchValue = request()->search;
                    $query->where(function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(JSON_EXTRACT(title, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"'])
                            ->orWhereRaw('LOWER(JSON_EXTRACT(description, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"']);
                    });
                }
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $orderColumn = request()->columns[request()->order[0]['column']]['data'];
                    $orderDirection = request()->order[0]['dir'];
                    $query->orderBy($orderColumn, $orderDirection);
                }
            })
            ->make(true);
    }

    public function fetchNews()
    {
        $language = request()->query('lang');
        if ($language) {
            app()->setLocale($language);
        }
        $newsCategory = request()->query('c') ?? "";
        $authorId = request()->query('author');
        $sort = request()->query('sort');
        $start = request()->get('start', 0);
        $length = request()->get('length', 24);
        if ($length > 24) {
            $length = 24;
        }

        $query = News::with(['author', 'category'])
            ->where('status', 'published')->whereNotNull('title->' . app()->getLocale())
            ->offset($start)
            ->limit($length);

        if (! empty($sort)) {
            if ($sort === 'oldest') {
                $query = $query->orderBy('news_date', 'asc');
            } else if ($sort === 'views') {
                $query = $query->orderBy('views', 'desc');
            } else if ($sort === 'random') {
                $query = $query->inRandomOrder();
            } else if ($sort === 'title') {
                $query = $query->orderBy('title->' . app()->getLocale(), 'asc');
            } else { // 'newest'
                $query = $query->orderBy('news_date', 'desc');
            }
        }

        if (! empty($authorId)) {
            $query = $query->where('author_id', $authorId);
        }

        // Filter by news category if provided
        if (!empty($newsCategory) && $newsCategory !== 'null') {
            $newsCategory = NewsCategory::where('slug->' . app()->getLocale(), '/' . ltrim($newsCategory, '/'))->first();
            if ($newsCategory) {
                $query = $query->where('news_category_id', $newsCategory->id);
            } else {
                $query = $query->whereRaw('1 = 0');
            }
        }

        return DataTables::of($query)
            ->editColumn('title', function ($news) {
                return $news->getTranslation('title', app()->getLocale());
            })
            ->editColumn('slug', function ($news) {
                return $news->getTranslation('slug', app()->getLocale());
            })
            ->editColumn('description', function ($news) {
                return $news->getTranslation('description', app()->getLocale());
            })
            ->editColumn('image', function ($news) {
                return FileHelper::addDomainPrefixIfValueIsAFile(TranslationHelper::firstAvailableValue($news, 'image'));
            })
            ->editColumn('news_category_name', function ($news) {
                return $news?->category?->getTranslation('name', app()->getLocale()) ?? null;
            })
            ->addColumn('news_category_slug', function ($news) {
                return $news?->category?->getTranslation('slug', app()->getLocale()) ?? null;
            })
            ->addColumn('author_name', function ($news) {
                return $news?->author?->getTranslation('name', app()->getLocale()) ?? null;
            })
            ->editColumn('full_url', function ($news) {
                return $news->full_url;
            })
            ->editColumn('news_date', function ($news) {
                return $news->news_date->format('Y-m-d');
            })
            ->filter(function ($query) {
                if (request()->has('search') && !empty(request()->search)) {
                    $searchValue = request()->search;
                    $query->where(function ($query) use ($searchValue) {
                        $query->whereRaw('LOWER(JSON_EXTRACT(title, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"'])
                            ->orWhereRaw('LOWER(JSON_EXTRACT(description, "$.' . app()->getLocale() . '")) like ?', ['"%' . strtolower($searchValue) . '%"']);
                    });
                }
            })
            ->addColumn('prefix', function() {
                $settingStore = new SettingStore;
                return $settingStore->findByKey(SettingKeyEnum::NEWS_PREFIX);
            })
            ->order(function ($query) {
                if (request()->has('order')) {
                    $orderColumn = request()->columns[request()->order[0]['column']]['data'];
                    $orderDirection = request()->order[0]['dir'];
                    $query->orderBy($orderColumn, $orderDirection);
                }
            })
            ->make(true);
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
        $bookGenres = BookGenre::where(function($query){
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
        $newsCategories = NewsCategory::where(function($query){
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
