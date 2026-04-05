<?php

namespace App\Modules\Articles\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Http\Resources\TagResource;
use App\Modules\Articles\Models\Tag;
use App\Modules\Shared\Helpers\TranslationHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Tag::class);

        if ($request->ajax()) {
            $data = Tag::visibleTo(auth()->user())->select(['id', 'name', 'slug']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . TranslationHelper::firstAvailableValue($item, 'name', false);
                })
                ->editColumn('slug', function ($item) {
                    $text = $item->getTranslation('slug', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . TranslationHelper::firstAvailableValue($item, 'slug', false);
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.tags.edit', $row->id);
                    $deleteUrl = route('admin.tags.destroy', $row->id);
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

        return view('articles::tag.index');
    }

    public function selectTags(Request $request)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $query = Tag::query();

        // Search functionality for Select2
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]);
            });
        }

        $tags = $query->paginate(50);

        return TagResource::collection($tags);
    }

    public function create()
    {
        $this->authorize('create', Tag::class);

        return view('articles::tag.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'name' => 'required|string|max:255',
        ]);

        $tag = new Tag;
        $tag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $tag->setTranslation('slug', app()->getLocale(), $request->input('slug') ?? Str::slug($request->input('name')));
        $tag->user_id = auth()->id();
        $tag->save();

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        $this->authorize('view', $tag);

        return view('articles::tag.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
        ]);
        $tag->user_id = auth()->id();
        $tag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $tag->setTranslation('slug', app()->getLocale(), $request->input('slug') ?? Str::slug($request->input('name')));
        $tag->save();

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorize('delete', $tag);

        $tag->delete();
        
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
