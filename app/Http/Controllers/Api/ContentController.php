<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Menu;
use App\Models\Page;
use App\Models\Article;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Language;
use App\Models\Redirect;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TranslationText;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\SettingResource;
use Barryvdh\Debugbar\Facades\Debugbar;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\RedirectResource;
use Yajra\DataTables\Facades\DataTables;
use DebugBar\DebugBar as DebugBarDebugBar;
use App\Http\Resources\TranslationTextResource;

class ContentController extends Controller
{
    public function fetchContent()
    {
        // TODO: move this to the languageapimiddleware
        $path = request()->path;
        if (empty($path)) {
            return response()->json(["status" => "error", "message" => "Missing \"path\" arguments"], 400);
        }
        if (!str_starts_with($path, '/')) {
            return response()->json(["status" => "error", "message" => "\"path\" should start with '/'"], 400);
        }
        $lang = request()->lang;
        $languages = Language::all();
        if ($languages->count() < 2) {
            // site is not multilingual
            // TODO: Page::where(") // do i need it???
            // $lang = Language::pluck('code')->first();
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
                $lang = Language::where('default', true)->pluck('code')->first();
                // return response()->json(["status" => "error", "message" => "Can not detect the language. " . $lang], 400);
            }
            app()->setLocale($lang);
        }

        $settings = Setting::all();
        $translationTexts = TranslationText::all();
        $menus = Menu::with('children')->where('parent_id', null)->orderBy('order')->get();
        $responseData = [
            'page' => collect(),
            'article' => collect(),
            'category' => collect(),
            'tag' => collect(),
            'auth' => collect(),
            'redirect' => collect(),
            'notfound' => false,
            'settings' => SettingResource::collection($settings),
            'languages' => LanguageResource::collection($languages),
            'menus' => MenuResource::collection($menus),
            'path' => $path,
            'lang' => $lang,
            'article_prefix' => '',
            'translation_texts' => TranslationTextResource::collection($translationTexts),
        ];

        if (auth()->check()) {
            $responseData["auth"] = UserResource::make(auth()->user());
        }

        $responseCode = 200;

        // Is Page
        $page = Page::with([
            'pageWidgets' => fn($query) => $query->orderBy('page_widget.position'),
            'pageWidgets.widget',
            'pageWidgets.fieldValues.field',
        ])->where('slug->' . app()->getLocale(), $path)->first();
        if ($page) {
            $responseData["page"] = PageResource::make($page);

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Category
        $category = Category::with(['children', 'parent'])->where('slug->' . app()->getLocale(), $path)->first(); //with(['articles' => fn($query) => $query->limit(10)])->
        if ($category) {
            $responseData["category"] = CategoryResource::make($category);

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Tag
        $tag = Tag::where('name->' . app()->getLocale(), $path)->first(); // with(['articles' => fn($query) => $query->limit(10)])->
        if ($tag) {
            $responseData["tag"] = TagResource::make($tag);

            return response()->json(['data' => $responseData], $responseCode);
        }

        //can be empty or 'articles' can be change depends on your need some websites like to have /articles before the slug of each article
        $articlePrefixSetting = $settings->where('key', 'article-prefix')->first();
        $articlePath = $path;
        if (!empty($articlePrefixSetting) && !empty($articlePrefixSetting->value)) {
            $articlePrefix = '/' . trim($articlePrefixSetting->value, '/');
            $articlePath = substr($path, strlen($articlePrefix));
        }

        info(app()->getLocale());
        info($articlePath);
        // Is Article
        $article = Article::with([
            'category',
            'tags',
            'page.pageWidgets' => fn($query) => $query->orderBy('page_widget.position'),
            'page.pageWidgets.widget',
            'page.pageWidgets.fieldValues.field',
        ])->where('slug->' . app()->getLocale(), $articlePath)->first();
        // return response()->json($article);
        if ($article) {
            // here check if category is correct do it, otherwise return 404
            $responseData["article_prefix"] = $articlePrefix;
            $responseData["article"] = ArticleResource::make($article);

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is Redirect
        $redirect = Redirect::where('from', $path)->where('language', $lang)->orderBy('id', 'desc')->first();
        if ($redirect) {
            $responseData["redirect"] = RedirectResource::make($redirect);

            return response()->json(['data' => $responseData], $responseCode);
        }

        // Is 404
        $responseCode = 404;
        $responseData["notfound"] = true;

        // $responseData["debuger"] = debugbar()->getData();
        return response()->json(['data' => $responseData], $responseCode);
    }

    public function fetchArticles()
    {
        $language = request()->language;
        if ($language) {
            app()->setLocale($language);
        }
        $category = request()->category ?? "";
        $tag = request()->tag ?? "";
        $sort = request()->sort;

        $query = Article::query();

        // Filter by category if provided
        if (!empty($category) && $category !== 'null') {
            $category = Category::where('slug->' . app()->getLocale(), '/' . Str::slug($category))->first();
            if ($category) {
                $query = $category->articles();
            } else {
                return response()->json(['status' => 'error', 'message' => 'Category does not exist: ' . request()->category], 404);
            }
        }

        // Filter by tag if provided
        if (!empty($tag)) {
            $tag = Tag::where('name->' . app()->getLocale(), $tag)->first();
            if ($tag) {
                $query = $tag->articles();
            }
        }

        if ($sort === 'newest') {
            $query = $query->orderBy('created_at', 'asc');
        } else {
            $query = $query->orderBy('created_at', 'desc');
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

    public function fetchCategories()
    {
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
