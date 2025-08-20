<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;

class PageController extends Controller
{
    public function show($pageId)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $page = Page::withAllWidgetData()->find($pageId);

        return response()->json(PageResource::make($page));
    }

    public function update($pageId)
    {
        $page = Page::where('slug', request()->slug)->first();
        if ($page) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is another page with this slug. choose another slug.'
            ]);
        }
        $page = Page::find($pageId);
        $lang = request()->lang ?: app()->getLocale();
        $page->setTranslation('title', $lang, request()->title);
        $page->setTranslation('slug', $lang, '/' . Str::slug(request()->slug));
        $page->status = request()->status;
        $page->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        // $pageType = 'page';
        if (!empty(request()->input('sitemap_exclude'))) {
            $page->sitemap_exclude = true;
        } else {
            $page->sitemap_exclude = null;
        }
        if (!empty(request()->input('sitemap_priority'))) {
            $page->sitemap_priority = request()->input('sitemap_priority');
        }
        if (!empty(request()->input('sitemap_change_frequency'))) {
            $page->sitemap_change_frequency = request()->input('sitemap_change_frequency');
        }
        if (!empty(request()->input('primary_language'))) {
            $page->primary_language = request()->input('primary_language');
            if (request()->input('primary_language') === 'default') {
                $page->primary_language = null;
            }
        }
        $page->save();

        return response()->json([
            'status' => 'success',
            'page' => $page
        ]);
    }

    public function delete(Page $page)
    {
        return response()->json([
            'request' => request()->all()
        ]);
    }
}
