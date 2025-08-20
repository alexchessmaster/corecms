<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Book;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Article;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Language;
use App\Models\Redirect;
use App\Models\BookGenre;
use App\Helpers\FileHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TranslationText;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\BookAuthorResource;
use App\Http\Resources\SettingResource;
use Barryvdh\Debugbar\Facades\Debugbar;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\RedirectResource;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Resources\BookGenreResource;
use DebugBar\DebugBar as DebugBarDebugBar;
use App\Http\Resources\TranslationTextResource;
use App\Models\BookAuthor;

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
        $languages = Language::all();
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
            'article_prefix' => '',
            'book' => collect(),
            'book_genre' => collect(),
            'book_prefix' => '',
            'tag' => collect(),
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

        //can be empty or 'articles' can be change depends on your need some websites like to have /articles before the slug of each article
        $articlePrefixSetting = $settings->where('key', 'article-prefix')->first();
        $articlePath = $path;
        // $articlePrefixSetting->value is "articles" by default
        if (!empty($articlePrefixSetting) && !empty($articlePrefixSetting->value)) {
            $articlePrefix = '/' . trim($articlePrefixSetting->value, '/');
            $articlePath = substr($path, strlen($articlePrefix));
        }

        // Is Article
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

        $bookPrefixSetting = $settings->where('key', 'book-prefix')->first();
        $bookPath = $path;
        // $bookPrefixSetting->value is "books" by default
        if (!empty($bookPrefixSetting) && !empty($bookPrefixSetting->value)) {
            $bookPrefix = '/' . trim($bookPrefixSetting->value, '/');
            $bookPath = substr($path, strlen($bookPrefix));
        }

        // Is Book
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

        $query = Book::with(['author', 'bookGenre'])->where('status', 'published')->whereNotNull('title->' . app()->getLocale());

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
                $query = $query->orderBy('created_at', 'desc');
            }
        }

        if (! empty($authorId)) {
            $query = $query->where('author_id', $authorId);
        }

        // Filter by book genre if provided
        if (!empty($bookGenre) && $bookGenre !== 'null') {
            $bookGenre = BookGenre::where('slug->' . app()->getLocale(), '/' . ltrim(Str::slug($bookGenre), '/'))->first();
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
                if (request()->has('search') && !empty(request()->search['value'])) {
                    $searchValue = request()->search['value'];
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

        $bookGenres = BookGenre::withCount('books')->get();
        return response()->json([
            'data' => BookGenreResource::collection($bookGenres)
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
}
