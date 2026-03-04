<?php

namespace App\Modules\News\Http\Controllers\Admin;

use App\Modules\News\Models\NewsTag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\News\Http\Resources\NewsTagResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsTagController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', NewsTag::class);

        if ($request->ajax()) {
            $data = NewsTag::visibleTo(auth()->user())->select(['id', 'name', 'slug']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('name', app()->getLocale(), true);
                })
                ->editColumn('slug', function ($item) {
                    $text = $item->getTranslation('slug', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('slug', app()->getLocale(), true);
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.news-tags.edit', $row->id);
                    $deleteUrl = route('admin.news-tags.destroy', $row->id);
                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>
                    <form action="' . $deleteUrl . '" method="POST" style="display: inline-block;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')">Delete</button>
                    </form>
                ';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('news::news_tag.index');
    }

    public function selectTags(Request $request)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $query = NewsTag::query();

        // Search functionality for Select2
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]);
            });
        }

        $tags = $query->paginate(50);

        return NewsTagResource::collection($tags);
    }

    public function create()
    {
        $this->authorize('create', NewsTag::class);

        return view('news::news_tag.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', NewsTag::class);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $newsTag = new NewsTag;
        $newsTag->user_id = auth()->id();
        $newsTag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $newsTag->setTranslation('slug', app()->getLocale(), Str::slug($request->input('name')));
        $newsTag->save();

        return redirect()->route('admin.news-tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(NewsTag $newsTag)
    {
        $this->authorize('update', $newsTag);

        return view('news::news_tag.edit', ['tag' => $newsTag]);
    }

    public function update(Request $request, NewsTag $newsTag)
    {
        $this->authorize('update', $newsTag);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $newsTag->user_id = auth()->id();
        $newsTag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $newsTag->setTranslation('slug', app()->getLocale(), Str::slug($request->input('name')));

        $newsTag->save();

        return redirect()->route('admin.news-tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(NewsTag $newsTag)
    {
        $this->authorize('delete', $newsTag);

        $newsTag->delete();
        
        return redirect()->route('admin.news-tags.index')->with('success', 'Tag deleted successfully.');
    }
}
