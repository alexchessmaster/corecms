<?php

namespace App\Modules\Books\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Books\Http\Requests\StoreBookRequest;
use App\Modules\Books\Http\Requests\UpdateBookRequest;
use App\Modules\Books\Models\Book;
use App\Modules\Books\Models\BookGenre;
use App\Modules\Shared\Helpers\StrHelper;
use App\Modules\Shared\Jobs\GenerateSitemapsJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

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
                ->addColumn('translated_languages', function ($book) {
                    $translations = $book->getTranslations('title');
                    $keys = array_keys($translations);
                    sort($keys);
                    return implode(' - ', $keys);
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
        $allWidgets = Widget::where('active', true)->orderBy('order')->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('books::book.create', compact('bookGenres', 'allWidgets', 'authToken'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Book::class);

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'pdf' => 'nullable|mimes:pdf|max:100000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
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
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/books');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $book->setTranslation('image', app()->getLocale(), '/uploads/books/' . $filename);
        }

        $book->setTranslation('title', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('title')));
        if (!empty($request->slug)) {
            $book->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }

        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $pdfFilename = Str::uuid() . '.' . $pdf->getClientOriginalExtension();
            $destinationPath = public_path('uploads/books');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $pdf->move($destinationPath, $pdfFilename);
            $book->setTranslation('pdf', app()->getLocale(), '/uploads/books/' . $pdfFilename);
        }

        $book->setTranslation('description', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('description')));
        $book->book_genre_id = $request->input('book_genre_id');

        // Sitemap
        if (!empty($request->input('sitemap_exclude'))) {
            $book->sitemap_exclude = true;
        } else {
            $book->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $book->sitemap_priority = $request->input('sitemap_priority');
        } else {
            $book->sitemap_priority = null;
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $book->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        } else {
            $book->sitemap_change_frequency = null;
        }
        // End sitemap

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

        if ($request->hasFile('pdf')) {
            \App\Modules\Books\Jobs\FillBookPageImageFolderJob::dispatch($book);
        }

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->route('admin.books.edit', [$book->id])->with('success', 'Book created successfully.');
    }

    public function edit($bookId)
    {
        $book = Book::withAllWidgetData()->findOrFail($bookId);
        $this->authorize('update', $book);
        $bookGenres = BookGenre::all();
        $allWidgets = Widget::where('active', true)->orderBy('order')->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('books::book.edit', compact('book', 'bookGenres', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, Book $book)
    {
        $this->authorize('update', $book);
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'pdf' => 'nullable|mimes:pdf|max:100000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
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
            $folderName = 'products';
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/books');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $book->setTranslation('image', app()->getLocale(), '/uploads/books/' . $filename);
        }

        if ($request->filled('remove_pdf')) {
            if ($book->getTranslation('pdf', app()->getLocale(), false)) {
                $oldPdfPath = public_path($book->getTranslation('pdf', app()->getLocale(), false));
                if (File::exists($oldPdfPath)) {
                    File::delete($oldPdfPath);
                }
            }
            $book->setTranslation('pdf', app()->getLocale(), null);
            $book->setTranslation('page_image_folder', app()->getLocale(), null);
        } elseif ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $pdfFilename = Str::uuid() . '.' . $pdf->getClientOriginalExtension();
            $destinationPath = public_path('uploads/books');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            if ($book->getTranslation('pdf', app()->getLocale(), false)) {
                $oldPdfPath = public_path($book->getTranslation('pdf', app()->getLocale(), false));
                if (File::exists($oldPdfPath)) {
                    File::delete($oldPdfPath);
                }
            }
            $pdf->move($destinationPath, $pdfFilename);
            $book->setTranslation('pdf', app()->getLocale(), '/uploads/books/' . $pdfFilename);
        }

        $book->setTranslation('title', app()->getLocale(), $request->input('title'));
        if ($request->slug !== $book->getTranslation('slug', app()->getLocale())) {
            $book->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }
        $book->setTranslation('description', app()->getLocale(), $request->input('description'));
        $book->book_genre_id = $request->input('book_genre_id');

        // Sitemap
        if (!empty($request->input('sitemap_exclude'))) {
            $book->sitemap_exclude = true;
        } else {
            $book->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $book->sitemap_priority = $request->input('sitemap_priority');
        } else {
            $book->sitemap_priority = null;
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $book->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        } else {
            $book->sitemap_change_frequency = null;
        }
        // End sitemap

        $book->status = $request->input('status');
        $book->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $book->published_year = $request->input('published_year');
        $book->author_id = $request->input('author_id') ?: null;
        $book->views = $request->input('views') || 0;
        $book->total_pages = $request->input('total_pages');

        $book->save();

        if ($request->hasFile('pdf')) {
            \App\Modules\Books\Jobs\FillBookPageImageFolderJob::dispatch($book);
        }

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->back()->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);
        $book->delete();

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully.');
    }
}
