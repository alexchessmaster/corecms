<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Widget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(str_contains(request()->path(), 'admin/templates')) {
            $pages = Page::where('type', 'template')->get();
            $pageType = 'template';
        } else {
            $pages = Page::where('type', 'page')->get();
            $pageType = 'page';
        }

        return view('admin.page.index', compact('pages', 'pageType'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pageType = 'page';
        if(str_contains(request()->path(), 'admin/templates')) {
            $pageType = 'template';
        }

        return view('admin.page.create', compact('pageType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $page = new Page;
        $page->setTranslation('title', app()->getLocale(), $request->title);
        $slug = '/' . Str::slug($request->slug);
        $page->setTranslation('slug', app()->getLocale(), $slug);
        $pageType = 'page';
        if(str_contains(request()->path(), 'admin/templates')) {
            $pageType = 'template';
        }
        $page->type = $pageType;
        $page->save();

        if($pageType === 'template') {
            return redirect()->route('admin.templates.edit', $page->id);
        }

        return redirect()->route('admin.pages.edit', $page->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($pageId)
    {
        $page = Page::findOrFail($pageId);
        $pageWidgets = $page->widgets;
        $pageType = $page->type;
        if($pageType === 'template') {
            $allWidgets = Widget::all();
        } else {
            $allWidgets = Widget::where('type', 'page')->get();
        }

        return view('admin.page.edit', compact('page', 'allWidgets', 'pageWidgets', 'pageType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        // $page->setTranslation('title', app()->getLocale(), request()->input('title'));
        // $page->setTranslation('slug', app()->getLocale(), request()->input('slug'));
        // if(!empty($request->input('sitemap_exclude'))){
        //     $page->sitemap_exclude = true;
        // } else {
        //     $page->sitemap_exclude = null;
        // }
        // if(!empty($request->input('sitemap_priority'))){
        //     $page->sitemap_priority = $request->input('sitemap_priority');
        // }
        // if(!empty($request->input('sitemap_change_frequency'))){
        //     $page->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        // }
        // $page->save();

        // return response()->json([
        //     'status' => 'ok',
        //     'message' => 'Page updated successfully.'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->back();
    }
}
