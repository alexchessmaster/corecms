<?php

namespace App\Modules\Pages\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Page;
use App\Modules\Widgets\Models\Widget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Modules\Pages\Http\Requests\StorePageRequest;
use App\Modules\Pages\Http\Requests\UpdatePageRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PageController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Page::class);

        if ($request->ajax()) {
            $pages = Page::visibleTo(auth()->user())->select(['id', 'title', 'slug', 'status']);

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

        return view('pages::page.index');
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
        $this->authorize('create', Page::class);

        return view('pages::page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $this->authorize('create', Page::class);

        $page = new Page;
        $page->user_id = auth()->id();
        $page->setTranslation('title', app()->getLocale(), $request->title);
        $slug = '/' . trim($request->slug);

        // Preserve slashes in slug: split, slugify each part, and join
        $lang = request()->lang ?: app()->getLocale();
        $slugParts = explode('/', request()->slug);
        $sluggedParts = array_map(fn($part) => trim($part), $slugParts);
        $slug = implode('/', $sluggedParts);
        $page->setTranslation('slug', $lang, '/' . ltrim($slug, '/'));

        $page->status = $request->status;
        $page->scheduled_at = $request->scheduled_at ? \Carbon\Carbon::parse($request->scheduled_at) : null;
        $page->save();

        return redirect()->route('admin.pages.edit', $page->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        $this->authorize('view', $page);

        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($pageId)
    {
        $page = Page::findOrFail($pageId);
        $this->authorize('update', $page);
        $page->user_id = auth()->id();
        $pageWidgets = $page->widgets;
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('pages::page.edit', compact('page', 'allWidgets', 'pageWidgets', 'authToken'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePageRequest $request, Page $page)
    {
        $this->authorize('update', $page);

        // Api/PageController.php
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $this->authorize('delete', $page);

        $page->delete();
        return redirect()->back();
    }
}
