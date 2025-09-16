<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Tag::class, 'tag');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Tag::select(['id', 'name']);
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
        return view('admin.tag.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag = new Tag;
        $tag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $tag->save();

        return redirect()->route('admin.tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag)
    {
        return view('admin.tag.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $tag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $tag->save();

        return redirect()->route('admin.tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')->with('success', 'Tag deleted successfully.');
    }
}
