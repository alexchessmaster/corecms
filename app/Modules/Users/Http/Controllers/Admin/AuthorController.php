<?php

namespace App\Modules\Users\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Users\Http\Resources\AuthorResource;
use Illuminate\Http\Request;
use App\Modules\Users\Models\Author;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', Author::class);

        if ($request->ajax() || false) {
            $data = Author::visibleTo(auth()->user())->select(['id', 'name', 'date_of_birth', 'date_of_death', 'nationality']);
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
                    $editUrl = route('admin.authors.edit', $row->id);
                    $deleteUrl = route('admin.authors.destroy', $row->id);
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

        return view('users::author.index');
    }

    public function selectAuthor(Request $request)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $query = Author::query();

        // Search functionality for Select2
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]);
            });
        }

        $authors = $query->paginate(50);

        return AuthorResource::collection($authors);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Author::class);

        return view('users::author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Author::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);

        $author = new Author;
        $author->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $author->image = '/uploads/images/' . $filename;
        }

        $author->setTranslation('name', app()->getLocale(), $request->input('name'));
        $author->date_of_birth = $request->input('date_of_birth');
        $author->date_of_death = $request->input('date_of_death');
        $author->setTranslation('nationality', app()->getLocale(), $request->input('nationality'));
        $author->setTranslation('biography', app()->getLocale(), $request->input('biography'));
        $author->save();

        return redirect()->route('admin.authors.index')->with('success', 'Author created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $this->authorize('view', $author);

        return view('users::author.show', compact('author'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Author $author)
    {
        $this->authorize('update', $author);

        return view('users::author.edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Author $author)
    {
        $this->authorize('update', $author);
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);
        $author->user_id = auth()->id();
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($author->image && File::exists(public_path($author->image))) {
                File::delete(public_path($author->image));
            }
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $author->image = '/uploads/images/' . $filename;
        }

        $author->setTranslation('name', app()->getLocale(), $request->input('name'));
        $author->date_of_birth = $request->input('date_of_birth');
        $author->date_of_death = $request->input('date_of_death');
        $author->setTranslation('nationality', app()->getLocale(), $request->input('nationality'));
        $author->setTranslation('biography', app()->getLocale(), $request->input('biography'));
        $author->save();

        return redirect()->route('admin.authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $this->authorize('delete', $author);

        // Delete associated image if exists
        if ($author->image && File::exists(public_path($author->image))) {
            File::delete(public_path($author->image));
        }

        // Handle products that might have this author
        // For example, set author_id to null or assign to a default author
        if (method_exists($author, 'products')) {
            $author->products()->update(['author_id' => null]);
        }
        if (method_exists($author, 'news')) {
            $author->news()->update(['author_id' => null]);
        }
        if (method_exists($author, 'articles')) {
            $author->articles()->update(['author_id' => null]);
        }

        $author->delete();

        return redirect()
            ->route('admin.authors.index')
            ->with('success', 'Author deleted successfully.');
    }
}
