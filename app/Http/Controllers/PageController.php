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
        $pages = Page::get();

        return view('admin.page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $page = new Page;
        $page->setTranslation('title', $request->getLocale(), $request->title);
        $slug = Str::slug($request->slug);
        if(! str_starts_with($slug, '/')){
            $slug = '/' . $slug;
        }
        $page->setTranslation('slug', $request->getLocale(), $slug);
        $page->save();

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
    public function edit(Page $page)
    {
        $allWidgets = Widget::all();
        $pageWidgets = $page->widgets;

        return view('admin.page.edit', compact('page', 'allWidgets', 'pageWidgets'));

        // $widgets = $page->widgets()->orderBy('order')->get();
        
        // return view('admin.page.widget-builder', compact('page', 'widgets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $page->title = request()->input('title');
        $page->slug = request()->input('slug');
        $page->save();
        
        return response()->json([
            'status' => 'ok',
            'message' => 'Page updated successfully.'
        ]);
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
