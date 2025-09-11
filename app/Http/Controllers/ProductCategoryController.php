<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Models\Language;
use App\Models\Widget;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {

        // Ensure /uncategorized product-category exists for each language
        $languages = Language::get();
        $languageCodes = collect($languages)->pluck('code')->toArray();

        // Create or update /uncategorized for each language using setTranslations()
        $nameTranslations = [];
        $slugTranslations = [];
        foreach ($languages as $lang) {
            $nameTranslations[$lang['code']] = 'Uncategorized';
            $slugTranslations[$lang['code']] = '/uncategorized';
        }
        foreach ($languages as $lang) {
            $productCategory = ProductCategory::whereRaw("JSON_EXTRACT(slug, '$." . $lang['code'] . "') = '/uncategorized'")
                ->first();
            if ($productCategory) {
                $currentName = $productCategory->getTranslation('name', $lang['code'], false);
                $productCategory->setTranslations('slug', $slugTranslations);
                $productCategory->save();

                break; // Only need to create one, since all translations are set at once
            }
        }


        if ($request->ajax()) {
            $data = ProductCategory::with('children')->select(['id', 'name', 'slug', 'parent_id']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('name', app()->getLocale(), true);
                })
                ->addColumn('parent', function ($item) {
                    return $item->parent?->name;
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.product-categories.edit', $row->id);
                    if (str_contains($row->slug, 'uncategorized')) {
                        return '<a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>';
                    }
                    $deleteUrl = route('admin.product-categories.destroy', $row->id);
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

        return view('admin.product_category.index');
    }

    public function create()
    {
        $productCategories = ProductCategory::whereNull('parent_id')->get();

        return view('admin.product_category.create', compact('productCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
        ]);

        $productCategory = new ProductCategory;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $productCategory->setTranslation('image', app()->getLocale(), '/uploads/images/' . $filename);
        }

        $productCategory->setTranslation('name', app()->getLocale(), $request->name);
        $productCategory->parent_id = $request->input('parent_id');
        $productCategory->setTranslation('description', app()->getLocale(), $request->input('description'));
        if (!empty($request->input('sitemap_exclude'))) {
            $productCategory->sitemap_exclude = true;
        } else {
            $productCategory->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $productCategory->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $productCategory->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $productCategory->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $productCategory->primary_language = null;
            }
        }
        $productCategory->save();

        return redirect()->route('admin.product-categories.index')->with('success', 'productCategory created successfully.');
    }

    public function edit($productCategoryId)
    {
        $productCategory = ProductCategory::withAllWidgetData()->findOrFail($productCategoryId);
        $productCategories = ProductCategory::whereNull('parent_id')->where('id', '!=', $productCategory->id)->get();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.product_category.edit', compact('productCategory', 'productCategories', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $productCategory->setTranslation('image', app()->getLocale(), '/uploads/images/' . $filename);
        }

        $productCategory->setTranslation('name', app()->getLocale(), $request->name);
        $productCategory->parent_id = $request->input('parent_id');
        $productCategory->description = $request->input('description');
        if (!empty($request->input('sitemap_exclude'))) {
            $productCategory->sitemap_exclude = true;
        } else {
            $productCategory->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $productCategory->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $productCategory->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $productCategory->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $productCategory->primary_language = null;
            }
        }
        $productCategory->save();

        return redirect()->route('admin.product-categories.index')->with('success', 'Product Category updated successfully.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        // uncategorized in en and other languages are the same
        $newProductCategory = $productCategory->parent ?? ProductCategory::where("slug->" . "en", '/uncategorized')->first();

        if (!$newProductCategory) {
            abort(404, 'The "uncategorized" product_category does not exist. Please create it before deleting categories.');
        }
        $updated = $productCategory->products()->update(['product_category_id' => $newProductCategory->id]);
        $productCategory->delete();
        return redirect()
            ->route('admin.product-categories.index')
            ->with('success', 'Product Category deleted successfully.');
    }
}
