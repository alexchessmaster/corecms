<?php

namespace App\Http\Controllers;

use App\Models\BookAuthor;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Requests\StoreBookAuthorRequest;
use App\Http\Requests\UpdateBookAuthorRequest;

class BookAuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || false) {
            $data = BookAuthor::select(['id', 'name', 'date_of_birth', 'date_of_death', 'nationality']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('name', app()->getLocale(), true);
                })
                ->editColumn('date_of_birth', function ($item) {
                    return $item->date_of_birth ? $item->date_of_birth->format('Y-m-d') : '-';
                })
                ->editColumn('date_of_death', function ($item) {
                    return $item->date_of_death ? $item->date_of_death->format('Y-m-d') : '-';
                })
                ->editColumn('nationality', function ($item) {
                    $text = $item->getTranslation('nationality', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('nationality', app()->getLocale(), true);
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.book-authors.edit', $row->id);
                    $deleteUrl = route('admin.book-authors.destroy', $row->id);
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

        return view('admin.book_author.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.book_author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);

        $bookAuthor = new BookAuthor;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $bookAuthor->image = '/uploads/images/' . $filename;
        }

        $bookAuthor->setTranslation('name', app()->getLocale(), $request->name);
        $bookAuthor->date_of_birth = $request->input('date_of_birth');
        $bookAuthor->date_of_death = $request->input('date_of_death');
        $bookAuthor->nationality = $request->input('nationality');
        $bookAuthor->biography = $request->input('biography');
        $bookAuthor->save();

        return redirect()->route('admin.book-authors.index')->with('success', 'Book Author created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BookAuthor $bookAuthor)
    {
        return view('admin.book_author.show', compact('bookAuthor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookAuthor $bookAuthor)
    {
        return view('admin.book_author.edit', compact('bookAuthor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BookAuthor $bookAuthor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($bookAuthor->image && File::exists(public_path($bookAuthor->image))) {
                File::delete(public_path($bookAuthor->image));
            }

            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $bookAuthor->image = '/uploads/images/' . $filename;
        }

        $bookAuthor->setTranslation('name', app()->getLocale(), $request->name);
        $bookAuthor->date_of_birth = $request->input('date_of_birth');
        $bookAuthor->date_of_death = $request->input('date_of_death');
        $bookAuthor->nationality = $request->input('nationality');
        $bookAuthor->biography = $request->input('biography');
        $bookAuthor->save();

        return redirect()->route('admin.book-authors.index')->with('success', 'Book Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookAuthor $bookAuthor)
    {
        // Delete associated image if exists
        if ($bookAuthor->image && File::exists(public_path($bookAuthor->image))) {
            File::delete(public_path($bookAuthor->image));
        }

        // You might want to handle books that have this author
        // For example, set author_id to null or assign to a default author
        $bookAuthor->books()->update(['author_id' => null]);
        
        $bookAuthor->delete();
        
        return redirect()
            ->route('admin.book-authors.index')
            ->with('success', 'Book Author deleted successfully.');
    }
}
