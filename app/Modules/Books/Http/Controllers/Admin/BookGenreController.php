<?php

namespace App\Modules\Books\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Widget;
use App\Models\Language;
use App\Modules\Books\Models\BookGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Modules\Books\Http\Requests\StoreBookGenreRequest;
use App\Modules\Books\Http\Requests\UpdateBookGenreRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookGenreController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', BookGenre::class);

        // Ensure /uncategorized book-genre exists for each language
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
            $bookGenre = BookGenre::whereRaw("JSON_EXTRACT(slug, '$." . $lang['code'] . "') = '/uncategorized'")
                ->first();
            if ($bookGenre) {
                $currentName = $bookGenre->getTranslation('name', $lang['code'], false);
                $bookGenre->setTranslations('slug', $slugTranslations);
                $bookGenre->save();

                break; // Only need to create one, since all translations are set at once
            }
        }


        if ($request->ajax()) {
            $data = BookGenre::visibleTo(auth()->user())->with('children')->select(['id', 'name', 'parent_id']);
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
                    if (str_contains($row->name, 'uncategorized')) {
                        return '';
                    }
                    $editUrl = route('admin.book_genres.edit', $row->id);
                    $deleteUrl = route('admin.book_genres.destroy', $row->id);
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

        return view('books::book_genre.index');
    }

    public function create()
    {
        $this->authorize('create', BookGenre::class);
        $bookGenres = BookGenre::whereNull('parent_id')->get();

        return view('books::book_genre.create', compact('bookGenres'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', BookGenre::class);
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
            'hide_from_frontend' => 'sometimes|boolean',
        ]);

        $bookGenre = new BookGenre;
        $bookGenre->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $bookGenre->setTranslation('image', app()->getLocale(), '/uploads/images/' . $filename);
        }

        $bookGenre->setTranslation('name', app()->getLocale(), $request->name);
        $bookGenre->setTranslation('slug', app()->getLocale(), $request->slug ?? \Str::slug($request->name));
        $bookGenre->parent_id = $request->input('parent_id');
        $bookGenre->hide_from_frontend = $request->boolean('hide_from_frontend');
        $bookGenre->setTranslation('description', app()->getLocale(), $request->input('description'));
        if (!empty($request->input('sitemap_exclude'))) {
            $bookGenre->sitemap_exclude = true;
        } else {
            $bookGenre->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $bookGenre->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $bookGenre->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $bookGenre->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $bookGenre->primary_language = null;
            }
        }
        $bookGenre->save();

        return redirect()->route('admin.book_genres.index')->with('success', 'bookGenre created successfully.');
    }

    public function edit($bookGenreId)
    {
        $bookGenre = BookGenre::withAllWidgetData()->findOrFail($bookGenreId);
        $this->authorize('update', $bookGenre);
        $bookGenres = BookGenre::whereNull('parent_id')->where('id', '!=', $bookGenre->id)->get();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('books::book_genre.edit', compact('bookGenre', 'bookGenres', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, BookGenre $bookGenre)
    {
        $this->authorize('update', $bookGenre);
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
            'hide_from_frontend' => 'sometimes|boolean',
        ]);
        $bookGenre->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $bookGenre->setTranslation('image', app()->getLocale(), '/uploads/images/' . $filename);
        }

        $bookGenre->setTranslation('name', app()->getLocale(), $request->name);
        $bookGenre->setTranslation('slug', app()->getLocale(), $request->slug ?? \Str::slug($request->name));
        $bookGenre->parent_id = $request->input('parent_id');
        $bookGenre->hide_from_frontend = $request->boolean('hide_from_frontend');
        $bookGenre->description = $request->input('description');
        if (!empty($request->input('sitemap_exclude'))) {
            $bookGenre->sitemap_exclude = true;
        } else {
            $bookGenre->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $bookGenre->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $bookGenre->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $bookGenre->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $bookGenre->primary_language = null;
            }
        }
        $bookGenre->save();

        return redirect()->route('admin.book_genres.index')->with('success', 'Book Genre updated successfully.');
    }

    public function destroy(BookGenre $bookGenre)
    {
        $this->authorize('delete', $bookGenre);
        // uncategorized in en and other languages are the same
        $newBookGenre = $bookGenre->parent ?? BookGenre::where("slug->" . "en", '/uncategorized')->first();
        if (!$newBookGenre) {
            abort(404, 'The "uncategorized" book_genre does not exist. Please create it before deleting categories.');
        }
        $bookGenre->books()->update(['book_genre_id' => $newBookGenre->id]);
        $bookGenre->delete();
        return redirect()
            ->route('admin.book_genres.index')
            ->with('success', 'Book Genre deleted successfully.');
    }
}
