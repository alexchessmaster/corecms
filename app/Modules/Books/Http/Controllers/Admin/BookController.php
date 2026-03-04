<?php

namespace App\Modules\Books\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Books\Models\Book;
use App\Models\Widget;
use App\Modules\Books\Models\BookGenre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Modules\Books\Http\Requests\StoreBookRequest;
use App\Modules\Books\Http\Requests\UpdateBookRequest;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class BookController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Book::class);

        if ($request->ajax()) {
            $books = Book::visibleTo(auth()->user())->select(['id', 'title', 'slug', 'book_genre_id', 'status'])->with(['bookGenre']);

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

        return view('books::book.index');
    }

    public function create()
    {
        $this->authorize('create', Book::class);

        $bookGenres = BookGenre::all();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('books::book.create', compact('bookGenres', 'allWidgets', 'authToken'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'book_genre_id' => 'required|exists:book_genres,id',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'status' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'published_year' => 'nullable|integer|min:-6000|max:2500',
            'book_author_id' => 'nullable|integer|exists:book_authors,id',
            'views' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:0',
        ]);

        $book = new Book;
        $book->user_id = auth()->id();
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
        // if (!empty($request->input('primary_language'))) {
        //     $book->primary_language = $request->input('primary_language');
        //     if ($request->input('primary_language') === 'default') {
        //         $book->primary_language = null;
        //     }
        // }
        $book->primary_language = app()->getLocale(); // Default to current locale
        $book->status = $request->input('status');
        $book->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $book->published_year = $request->input('published_year');
        if (!empty($request->input('book_author_id'))) {
            $book->author_id = $request->input('book_author_id');
        }
        $book->views = $request->input('views') || 0;
        $book->total_pages = $request->input('total_pages');

        $book->save();

        return redirect()->route('admin.books.edit', [$book->id])->with('success', 'Book created successfully.');
    }

    public function edit($bookId)
    {
        $book = Book::withAllWidgetData()->findOrFail($bookId);
        $this->authorize('update', $book);
        $bookGenres = BookGenre::all();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('books::book.edit', compact('book', 'bookGenres', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'book_genre_id' => 'required|exists:book_genres,id',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'status' => 'required|string',
            'scheduled_at' => 'nullable|date',
            'published_year' => 'nullable|integer|min:-6000|max:2500',
            'book_author_id' => 'nullable|integer|exists:book_authors,id',
            'views' => 'nullable|integer|min:0',
            'total_pages' => 'nullable|integer|min:0',
        ]);
        $book->user_id = auth()->id();
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
        // if (!empty($request->input('primary_language'))) {
        //     $book->primary_language = $request->input('primary_language');
        //     if ($request->input('primary_language') === 'default') {
        //         $book->primary_language = null;
        //     }
        // }
        $book->status = $request->input('status');
        $book->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $book->published_year = $request->input('published_year');
        if (!empty($request->input('book_author_id'))) {
            $book->author_id = $request->input('book_author_id');
        }
        $book->views = $request->input('views') || 0;
        $book->total_pages = $request->input('total_pages');

        $book->save();

        return redirect()->back()->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }
}
