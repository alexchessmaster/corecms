<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Category::with('children')->select(['id', 'name', 'parent_id']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated-';
                })
                ->addColumn('parent', function($item){
                    return $item->parent?->name;
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.categories.edit', $row->id);
                    $deleteUrl = route('admin.categories.destroy', $row->id);
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

        return view('admin.category.index');
    }

    public function create()
    {
        $categories = Category::whereNull('parent_id')->get();
        return view('admin.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'exclude_from_sitemap' => 'nullable|boolean',
        ]);

        $category = new Category;
        $category->setTranslation('name', app()->getLocale(), $request->name);
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
        $category->exclude_from_sitemap = $request->input('exclude_from_sitemap');
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(Category $category)
    {
        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        return view('admin.category.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'exclude_from_sitemap' => 'nullable|boolean',
        ]);

        $category->setTranslation('name', app()->getLocale(), $request->name);
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
        $category->exclude_from_sitemap = $request->input('exclude_from_sitemap');
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $newCategory = $category->parent ?? Category::where("slug->" . app()->getLocale(), '/uncategorized')->firstOrFail();
        $category->articles()->update(['category_id' => $newCategory->id]);
        $category->delete();
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
