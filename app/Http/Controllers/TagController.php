<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TagController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', Tag::class);

        if ($request->ajax()) {
            $data = Tag::visibleTo(auth()->user())->select(['id', 'name']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('name', app()->getLocale(), true);
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

        return view('admin.tag.index');
    }

    public function create()
    {
        $this->authorize('create', Tag::class);
        
        return view('admin.tag.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Tag::class);
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = new Tag;
        $tag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $tag->user_id = auth()->id();
        $tag->save();

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        $this->authorize('view', $tag);
        
        return view('admin.tag.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorize('update', $tag);
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $tag->user_id = auth()->id();
        $tag->setTranslation('name', app()->getLocale(), $request->input('name'));
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
