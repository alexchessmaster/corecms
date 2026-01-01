<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use App\Models\Category;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CategoryController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $languages = Language::get();
        $languageCodes = collect($languages)->pluck('code')->toArray();

        $nameTranslations = [];
        $slugTranslations = [];
        foreach ($languages as $lang) {
            $nameTranslations[$lang['code']] = 'Uncategorized';
            $slugTranslations[$lang['code']] = '/uncategorized';
        }
        foreach ($languages as $lang) {
            $category = Category::whereRaw("JSON_EXTRACT(slug, '$." . $lang['code'] . "') = '/uncategorized'")
                ->first();
            if($category){
                $currentName = $category->getTranslation('name', $lang['code'], false);
                
                // Only set translations if current name is Uncategorized, null, or empty
                if (empty($currentName) || $currentName === 'Uncategorized') {
                    $category->setTranslations('name', $nameTranslations);
                }
                
                $category->setTranslations('slug', $slugTranslations);
                $category->save();

                break;
            }
        }


        if ($request->ajax()) {
            $data = Category::visibleTo(auth()->user())->with('children')->select(['id', 'name', 'parent_id']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('name', app()->getLocale(), true);
                })
                ->addColumn('parent', function($item){
                    return $item->parent?->name;
                })
                ->addColumn('actions', function ($row) {
                    if(str_contains($row->name, 'uncategorized')){
                        return '';
                    }
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
        $this->authorize('create', Category::class);

        $categories = Category::whereNull('parent_id')->get();

        return view('admin.category.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
        ]);

        $category = new Category;
        $category->user_id = auth()->id();
        $category->setTranslation('name', app()->getLocale(), $request->name);
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
        if(!empty($request->input('sitemap_exclude'))){
            $category->sitemap_exclude = true;
        } else {
            $category->sitemap_exclude = null;
        }
        if(!empty($request->input('sitemap_priority'))){
            $category->sitemap_priority = $request->input('sitemap_priority');
        }
        if(!empty($request->input('sitemap_change_frequency'))){
            $category->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $category->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $category->primary_language = null;
            }
        }
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit($categoryId)
    {
        
        $category = Category::withAllWidgetData()->findOrFail($categoryId);
        $this->authorize('update', $category);

        $categories = Category::whereNull('parent_id')->where('id', '!=', $category->id)->get();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.category.edit', compact('category', 'categories', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
        ]);
        $category->user_id = auth()->id();
        $category->setTranslation('name', app()->getLocale(), $request->name);
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->input('description');
        if(!empty($request->input('sitemap_exclude'))){
            $category->sitemap_exclude = true;
        } else {
            $category->sitemap_exclude = null;
        }
        if(!empty($request->input('sitemap_priority'))){
            $category->sitemap_priority = $request->input('sitemap_priority');
        }
        if(!empty($request->input('sitemap_change_frequency'))){
            $category->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $category->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $category->primary_language = null;
            }
        }
        $category->save();

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        
        // uncategorized in en and other languages are the same
        $newCategory = $category->parent ?? Category::where("slug->" . "en", '/uncategorized')->first();
        if (!$newCategory) {
            abort(404, 'The "uncategorized" category does not exist. Please create it before deleting categories.');
        }
        $category->articles()->update(['category_id' => $newCategory->id]);
        $category->delete();
        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
