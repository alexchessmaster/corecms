<?php

namespace App\Modules\News\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\News\Http\Resources\NewsAuthorResource;
use Illuminate\Support\Facades\File;
use App\Modules\News\Models\NewsAuthor;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsAuthorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', NewsAuthor::class);

        if ($request->ajax() || false) {
            $data = NewsAuthor::visibleTo(auth()->user())->select(['id', 'name', 'date_of_birth', 'date_of_death', 'nationality']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('name', app()->getLocale(), true);
                })

                ->editColumn('nationality', function ($item) {
                    $text = $item->getTranslation('nationality', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('nationality', app()->getLocale(), true);
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.news-authors.edit', $row->id);
                    $deleteUrl = route('admin.news-authors.destroy', $row->id);
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

        return view('news::news_author.index');
    }

    public function selectAuthor(Request $request)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $query = NewsAuthor::query();

        // Search functionality for Select2
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]);
            });
        }

        $authors = $query->paginate(50);

        return NewsAuthorResource::collection($authors);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', NewsAuthor::class);

        return view('news::news_author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', NewsAuthor::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);

        $newsAuthor = new NewsAuthor;
        $newsAuthor->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $newsAuthor->image = '/uploads/images/' . $filename;
        }

        $newsAuthor->setTranslation('name', app()->getLocale(), $request->input('name'));
        $newsAuthor->date_of_birth = $request->input('date_of_birth');
        $newsAuthor->date_of_death = $request->input('date_of_death');
        $newsAuthor->setTranslation('nationality', app()->getLocale(), $request->input('nationality'));
        $newsAuthor->setTranslation('biography', app()->getLocale(), $request->input('biography'));
        $newsAuthor->save();

        return redirect()->route('admin.news-authors.index')->with('success', 'News Author created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(NewsAuthor $newsAuthor)
    {
        $this->authorize('view', $newsAuthor);

        return view('news::news_author.show', compact('newsAuthor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NewsAuthor $newsAuthor)
    {
        $this->authorize('update', $newsAuthor);

        return view('news::news_author.edit', compact('newsAuthor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NewsAuthor $newsAuthor)
    {
        $this->authorize('update', $newsAuthor);
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);
        $newsAuthor->user_id = auth()->id();
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($newsAuthor->image && File::exists(public_path($newsAuthor->image))) {
                File::delete(public_path($newsAuthor->image));
            }
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $newsAuthor->image = '/uploads/images/' . $filename;
        }

        $newsAuthor->setTranslation('name', app()->getLocale(), $request->input('name'));
        $newsAuthor->date_of_birth = $request->input('date_of_birth');
        $newsAuthor->date_of_death = $request->input('date_of_death');
        $newsAuthor->setTranslation('nationality', app()->getLocale(), $request->input('nationality'));
        $newsAuthor->setTranslation('biography', app()->getLocale(), $request->input('biography'));
        $newsAuthor->save();

        return redirect()->route('admin.news-authors.index')->with('success', 'News Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NewsAuthor $newsAuthor)
    {
        $this->authorize('delete', $newsAuthor);

        // Delete associated image if exists
        if ($newsAuthor->image && File::exists(public_path($newsAuthor->image))) {
            File::delete(public_path($newsAuthor->image));
        }

        // Handle news that have this author
        if (method_exists($newsAuthor, 'news')) {
            $newsAuthor->news()->update(['author_id' => null]);
        }

        $newsAuthor->delete();

        return redirect()
            ->route('admin.news-authors.index')
            ->with('success', 'News Author deleted successfully.');
    }
}
