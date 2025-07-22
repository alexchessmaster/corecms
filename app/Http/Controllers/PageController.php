<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Widget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use Yajra\DataTables\Facades\DataTables;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $pages = Page::select(['id', 'title', 'slug', 'status']);

            return DataTables::of($pages)
                ->editColumn('title', function ($page) {
                    $title = $page->getTranslation('title', app()->getLocale(), false);
                    return $title ?: '-Not translated- ' . $page->getTranslation('title', app()->getLocale(), true);
                })
                ->editColumn('slug', function ($page) {
                    $slug = $page->getTranslation('slug', app()->getLocale(), false);
                    return $slug ?: '-Not translated- ' . $page->getTranslation('slug', app()->getLocale(), true);
                })
                ->editColumn('status', function ($page) {
                    return $page->status;
                })
                ->addColumn('actions', function ($page) {
                    return '
                    <a href="' . route('admin.pages.edit', $page) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="' . route('admin.pages.destroy', $page) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['categories', 'tags', 'actions', 'title'])
                ->make(true);
        }

        return view('admin.page.index');
    }
    // public function index()
    // {
    //     $pages = Page::get();

    //     return view('admin.page.index', compact('pages'));
    // }

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
        $page->setTranslation('title', app()->getLocale(), $request->title);
        $slug = '/' . Str::slug($request->slug);
        $page->setTranslation('slug', app()->getLocale(), $slug);
        $page->status = $request->status;
        $page->scheduled_at = $request->scheduled_at ? \Carbon\Carbon::parse($request->scheduled_at) : null;
        // $pageType = 'page';
        // if (str_contains(request()->path(), 'admin/templates')) {
        //     $pageType = 'template';
        // }
        // $page->type = $pageType;
        $page->save();

        // if ($pageType === 'template') {
        //     return redirect()->route('admin.templates.edit', $page->id);
        // }

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
        // $pageType = $page->type;
        // if ($pageType === 'template') {
        //     $allWidgets = Widget::where('active', true)->get();
        // } else {
        $allWidgets = Widget::where('active', true)->get();
        // }

        return view('admin.page.edit', compact('page', 'allWidgets', 'pageWidgets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->back();
    }
}
