<?php

namespace App\Modules\Pages\Http\Controllers\Api;

use App\Modules\Pages\Models\Page;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\Pages\Http\Resources\PageResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PageController extends Controller
{
    use AuthorizesRequests;

    public function show($pageId)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }
        $page = Page::withAllWidgetData()->find($pageId);
        $this->authorize('view', $page);

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
        $this->authorize('update', $page);
        $lang = request()->lang ?: app()->getLocale();
        $page->setTranslation('title', $lang, request()->title);

        // Preserve slashes in slug: split, slugify each part, and join
        $slugParts = explode('/', request()->slug);
        $sluggedParts = array_map(fn($part) => trim($part), $slugParts);
        $slug = implode('/', $sluggedParts);
        $page->setTranslation('slug', $lang, '/' . ltrim($slug, '/'));

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
