<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\MenuResource;
use App\Http\Resources\PageResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\UserResource;
use App\Models\Article;
use App\Models\Category;
use Barryvdh\Debugbar\Facades\Debugbar;
use DebugBar\DebugBar as DebugBarDebugBar;
use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\json;

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
                return response()->json(["status" => "error", "message" => "Can not detect the language"], 400);
            }
            app()->setLocale($lang);
        }

        $settings = Setting::all();
        $languages = Language::all();
        $menus = Menu::with('children')->where('parent_id', null)->orderBy('order')->get();
        $responseData = [
            
            'page' => collect(),
            'article' => collect(),
            'category' => collect(),
            'tag' => collect(),
            'auth' => collect(),

            'settings' => SettingResource::collection($settings),
            'languages' => LanguageResource::collection($languages),
            'menus' => MenuResource::collection($menus),
        ];

        if (auth()->check()) {
            $responseData["auth"] = UserResource::make(auth()->user());
        }
        
        
        $responseCode = 200;
        
        $page = Page::with([
            'pageWidgets' => fn($query) => $query->orderBy('page_widget.position'),
            'pageWidgets.widget',
            'pageWidgets.fieldValues.field',
        ])->where('slug->' . app()->getLocale(), $path)->first();
        if ($page) {
            $responseData["page"] = PageResource::make($page);

            return response()->json(['data' => $responseData], $responseCode);
        }

        $category = Category::with(['articles' => fn($query) => $query->limit(10)])->where('slug', $path);


        
        $article = Article::with(['category', 'tags', 
            'page.pageWidgets' => fn($query) => $query->orderBy('page_widget.position'),
            'page.pageWidgets.widget',
            'page.pageWidgets.fieldValues.field',
        ])->where('slug->' . app()->getLocale(), $path)->first();
        if($article) {
            //make a front-end for this backend and check if we have everything we need

            // TODO: plan for nested categories
            // first check if nested categories, return nested category page with related articles, then check for articles
            // TODO: if category is not correct, should return 404
            // TODO: add slug to tag
            // TODO: if tag is not correct, should return 404
            // TODO: redirect table
            // TODO: not-found table
            // TODO: db-back-up script
            // TODO: visitors and statistic 

            // here check if category is correct do it, otherwise return 404
            $responseData["article"] = ArticleResource::make($article);

            return response()->json(['data' => $responseData], $responseCode);            
        }



        // $responseData["debuger"] = debugbar()->getData();

        return response()->json(['data' => $responseData], $responseCode);
    }

    public function fetchArticles()
    {
        // $category = "category slug"
        // $sort = "newest", "most_views", "oldest", ...
        // $limit = 10;
        // $tag = "Tag Name";
        $limit = request()->limit ?? 10;
        $sort = request()->sort ?? "newest";
        $category = request()->category ?? "";
        $tag = request()->tag ?? ""; // tag is common, not tags

        $query = Article::query();
        if(!empty($category)) {
            $category = Category::where('slug', $category)->first();
            $query = $category->articles()->query(); // TODO check later if it's working
        }
        if(!empty($tag)) {
            $tag = Tag::where('name', $tag)->first();
            $query = $tag->articles()->query(); // TODO check later if it's working
        }
        if($sort === 'newest') {
            $query->orderBy('created_at', 'desc');
        } else if ($sort === "most_view") {
            // count views on articles
        }
        $query->limit($limit);
        $articles = $query->get();

        return ArticleResource::collection($articles);
    }
}
