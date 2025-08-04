<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Widget;
use App\Models\BookGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $books = Book::select(['id', 'title', 'slug', 'book_genre_id'])->with(['bookGenre']);

            return DataTables::of($books)
                ->editColumn('title', function ($book) {
                    $title = $book->getTranslation('title', app()->getLocale(), false);
                    return $title ?: '-Not translated-' . $book->getTranslation('title', app()->getLocale(), true);
                })
                ->addColumn('book_genre', function ($book) {
                    return $book->bookGenre->getTranslation('name', app()->getLocale());
                })
                ->addColumn('actions', function ($book) {
                    return '
                    <a href="' . route('admin.books.edit', $book) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="' . route('admin.books.destroy', $book) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['categories', 'tags', 'actions', 'title'])
                ->make(true);
        }

        return view('admin.book.index');
    }

    public function create()
    {
        $bookGenres = BookGenre::all();
        $allWidgets = Widget::where('active', true)->get();

        return view('admin.book.create', compact('bookGenres', 'allWidgets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'book_genre_id' => 'required|exists:book_genres,id',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'published_year' => 'nullable|integer|min:-6000|max:2500',
            'author' => 'nullable|string|max:255',
            'views' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:0',
        ]);

        $book = new Book;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/books');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $book->setTranslation('image', app()->getLocale(), '/uploads/books/' . $filename);
        }

        $book->setTranslation('title', app()->getLocale(), $request->input('title'));
        $book->setTranslation('description', app()->getLocale(), $request->input('description'));
        $book->book_genre_id = $request->input('book_genre_id');
        if (!empty($request->input('sitemap_exclude'))) {
            $book->sitemap_exclude = true;
        } else {
            $book->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $book->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $book->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $book->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $book->primary_language = null;
            }
        }
        $book->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $book->published_year = $request->input('published_year');
        $book->author = $request->input('author');
        $book->views = $request->input('views') || 0;
        $book->total_pages = $request->input('total_pages');

        $book->save();

        return redirect()->route('admin.books.edit', [$book->id])->with('success', 'Book created successfully.');
    }

    public function edit($bookId)
    {
        $book = Book::withAllWidgetData()->findOrFail($bookId);
        $bookGenres = BookGenre::all();
        $allWidgets = Widget::where('active', true)->get();

        return view('admin.book.edit', compact('book', 'bookGenres', 'allWidgets'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'book_genre_id' => 'required|exists:book_genres,id',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'published_year' => 'nullable|integer|min:-6000|max:2500',
            'author' => 'nullable|string|max:255',
            'views' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/books');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $book->setTranslation('image', app()->getLocale(), '/uploads/books/' . $filename);
        }

        $book->setTranslation('title', app()->getLocale(), $request->input('title'));
        $book->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        $book->setTranslation('description', app()->getLocale(), $request->input('description'));
        $book->book_genre_id = $request->input('book_genre_id');
        if (!empty($request->input('sitemap_exclude'))) {
            $book->sitemap_exclude = true;
        } else {
            $book->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $book->sitemap_priority = $request->input('sitemap_priority');
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $book->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        if (!empty($request->input('primary_language'))) {
            $book->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $book->primary_language = null;
            }
        }
        $book->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $book->published_year = $request->input('published_year');
        $book->author = $request->input('author');
        $book->views = $request->input('views') || 0;
        $book->total_pages = $request->input('total_pages');

        $book->save();

        return redirect()->back()->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }
}
