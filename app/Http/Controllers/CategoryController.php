<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StorecategoryRequest;
use App\Http\Requests\UpdatecategoryRequest;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('children')->get();
        return view('admin.category.index', compact('categories'));
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
        ]);

        $category = new Category;
        $category->setTranslation('name', app()->getLocale(), $request->name);
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
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
            // 'slug' => 'required|string|max:255',
        ]);

        $category->setTranslation('name', app()->getLocale(), $request->name);
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
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
