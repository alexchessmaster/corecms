<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use App\Models\Language;
use App\Models\BookGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreBookGenreRequest;
use App\Http\Requests\UpdateBookGenreRequest;

class BookGenreController extends Controller
{
    public function index(Request $request)
    {

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
                
                // Only set translations if current name is Uncategorized, null, or empty
                if (empty($currentName) || $currentName === 'Uncategorized') {
                    // $bookGenre->setTranslations('name', $nameTranslations);
                }
                
                $bookGenre->setTranslations('slug', $slugTranslations);
                $bookGenre->save();

                break; // Only need to create one, since all translations are set at once
            }
        }


        if ($request->ajax()) {
            $data = BookGenre::with('children')->select(['id', 'name', 'parent_id']);
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

        return view('admin.book_genre.index');
    }

    public function create()
    {
        $bookGenres = BookGenre::whereNull('parent_id')->get();

        return view('admin.book_genre.create', compact('bookGenres'));
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

        $bookGenre = new BookGenre;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $bookGenre->setTranslation('image', app()->getLocale(), '/uploads/images/' . $filename);
        }

        $bookGenre->setTranslation('name', app()->getLocale(), $request->name);
        $bookGenre->parent_id = $request->input('parent_id');
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
        $bookGenres = BookGenre::whereNull('parent_id')->where('id', '!=', $bookGenre->id)->get();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.book_genre.edit', compact('bookGenre', 'bookGenres', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, BookGenre $bookGenre)
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
            $bookGenre->setTranslation('image', app()->getLocale(), '/uploads/images/' . $filename);
        }

        $bookGenre->setTranslation('name', app()->getLocale(), $request->name);
        $bookGenre->parent_id = $request->input('parent_id');
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
