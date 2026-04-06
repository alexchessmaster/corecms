<?php

namespace App\Modules\News\Http\Controllers\Admin;

use App\Modules\Widgets\Models\Widget;
use App\Modules\Languages\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\Controller;
use App\Modules\News\Models\NewsCategory;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsCategoryController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', NewsCategory::class);

        // Ensure /uncategorized news-category exists for each language
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
            $newsCategory = NewsCategory::whereRaw("JSON_EXTRACT(slug, '$." . $lang['code'] . "') = '/uncategorized'")
                ->first();
            if ($newsCategory) {
                $currentName = $newsCategory->getTranslation('name', $lang['code'], false);
                $newsCategory->setTranslations('slug', $slugTranslations);
                $newsCategory->save();

                break; // Only need to create one, since all translations are set at once
            }
        }


        if ($request->ajax()) {
            $data = NewsCategory::visibleTo(auth()->user())->with('children')->select(['id', 'name', 'slug', 'parent_id']);
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
                    $editUrl = route('admin.news-categories.edit', $row->id);
                    if (str_contains($row->slug, 'uncategorized')) {
                        return '<a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>';
                    }
                    $deleteUrl = route('admin.news-categories.destroy', $row->id);
                    return '<a href="' . $editUrl . '" class="btn btn-sm btn-primary">Edit</a>';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('news::news_category.index');
    }

    public function create()
    {
        $this->authorize('create', NewsCategory::class);

        $newsCategories = NewsCategory::whereNull('parent_id')->get();
        return view('news::news_category.create', compact('newsCategories'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', NewsCategory::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:news_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
            'hide_from_frontend' => 'sometimes|boolean',
        ]);

        $newsCategory = new NewsCategory;
        $newsCategory->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/news_categories');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $newsCategory->setTranslation('image', app()->getLocale(), '/uploads/news_categories/' . $filename);
        }

        $newsCategory->setTranslation('name', app()->getLocale(), $request->name);
        $newsCategory->setTranslation('slug', app()->getLocale(), $request->slug ?? \Str::slug($request->name));
        $newsCategory->parent_id = $request->input('parent_id');
        $newsCategory->hide_from_frontend = $request->boolean('hide_from_frontend');
        $newsCategory->setTranslation('description', app()->getLocale(), $request->input('description'));
        if (!empty($request->input('sitemap_exclude'))) {
            $newsCategory->sitemap_exclude = true;
        } else {
            $newsCategory->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $newsCategory->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $newsCategory->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $newsCategory->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $newsCategory->primary_language = null;
            }
        }
        $newsCategory->save();

        return redirect()->route('admin.news-categories.index')->with('success', 'News Category created successfully.');
    }

    public function edit($newsCategoryId)
    {
        $newsCategory = NewsCategory::withAllWidgetData()->findOrFail($newsCategoryId);
        $this->authorize('update', $newsCategory);

        $newsCategories = NewsCategory::whereNull('parent_id')->where('id', '!=', $newsCategory->id)->get();
        $allWidgets = Widget::where('active', true)->orderBy('order')->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('news::news_category.edit', compact('newsCategory', 'newsCategories', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, NewsCategory $newsCategory)
    {
        $this->authorize('update', $newsCategory);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'parent_id' => 'nullable|exists:news_categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
            'hide_from_frontend' => 'sometimes|boolean',
        ]);
        $newsCategory->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/news_categories');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $newsCategory->setTranslation('image', app()->getLocale(), '/uploads/news_categories/' . $filename);
        }
        $newsCategory->setTranslation('name', app()->getLocale(), $request->name);
        $newsCategory->setTranslation('slug', app()->getLocale(), $request->slug ?? \Str::slug($request->name));
        $newsCategory->parent_id = $request->input('parent_id');
        $newsCategory->hide_from_frontend = $request->boolean('hide_from_frontend');
        $newsCategory->description = $request->input('description');
        if (!empty($request->input('sitemap_exclude'))) {
            $newsCategory->sitemap_exclude = true;
        } else {
            $newsCategory->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $newsCategory->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $newsCategory->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $newsCategory->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $newsCategory->primary_language = null;
            }
        }
        $newsCategory->save();

        return redirect()->route('admin.news-categories.index')->with('success', 'News Category updated successfully.');
    }

    public function destroy(NewsCategory $newsCategory)
    {
        $this->authorize('delete', $newsCategory);

        // uncategorized in en and other languages are the same
        $newNewsCategory = $newsCategory->parent ?? NewsCategory::where("slug->" . "en", '/uncategorized')->first();

        if (!$newNewsCategory) {
            abort(404, 'The "uncategorized" news_category does not exist. Please create it before deleting categories.');
        }
        $updated = $newsCategory->news()->update(['news_category_id' => $newNewsCategory->id]);
        $newsCategory->delete();
        return redirect()
            ->route('admin.news-categories.index')
            ->with('success', 'News Category deleted successfully.');
    }
}
