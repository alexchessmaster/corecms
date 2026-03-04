<?php

namespace App\Modules\Products\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Resources\ProductAuthorResource;
use Illuminate\Http\Request;
use App\Modules\Products\Models\ProductAuthor;
use Illuminate\Support\Facades\File;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductAuthorController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', ProductAuthor::class);

        if ($request->ajax() || false) {
            $data = ProductAuthor::visibleTo(auth()->user())->select(['id', 'name', 'date_of_birth', 'date_of_death', 'nationality']);
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
                    $editUrl = route('admin.product-authors.edit', $row->id);
                    $deleteUrl = route('admin.product-authors.destroy', $row->id);
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

        return view('products::product_author.index');
    }

    public function selectAuthor(Request $request)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $query = ProductAuthor::query();

        // Search functionality for Select2
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]);
            });
        }

        $authors = $query->paginate(50);

        return ProductAuthorResource::collection($authors);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', ProductAuthor::class);

        return view('products::product_author.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', ProductAuthor::class);

        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);

        $productAuthor = new ProductAuthor;
        $productAuthor->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $productAuthor->image = '/uploads/images/' . $filename;
        }

        $productAuthor->setTranslation('name', app()->getLocale(), $request->input('name'));
        $productAuthor->date_of_birth = $request->input('date_of_birth');
        $productAuthor->date_of_death = $request->input('date_of_death');
        $productAuthor->setTranslation('nationality', app()->getLocale(), $request->input('nationality'));
        $productAuthor->setTranslation('biography', app()->getLocale(), $request->input('biography'));
        $productAuthor->save();

        return redirect()->route('admin.product-authors.index')->with('success', 'Product Author created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProductAuthor $productAuthor)
    {
        $this->authorize('view', $productAuthor);

        return view('products::product_author.show', compact('productAuthor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductAuthor $productAuthor)
    {
        $this->authorize('update', $productAuthor);

        return view('products::product_author.edit', compact('productAuthor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductAuthor $productAuthor)
    {
        $this->authorize('update', $productAuthor);
        $request->validate([
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'date_of_death' => 'nullable|date|after_or_equal:date_of_birth',
            'nationality' => 'nullable|string|max:255',
            'biography' => 'nullable|string',
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:2048',
        ]);
        $productAuthor->user_id = auth()->id();
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($productAuthor->image && File::exists(public_path($productAuthor->image))) {
                File::delete(public_path($productAuthor->image));
            }
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $productAuthor->image = '/uploads/images/' . $filename;
        }

        $productAuthor->setTranslation('name', app()->getLocale(), $request->input('name'));
        $productAuthor->date_of_birth = $request->input('date_of_birth');
        $productAuthor->date_of_death = $request->input('date_of_death');
        $productAuthor->setTranslation('nationality', app()->getLocale(), $request->input('nationality'));
        $productAuthor->setTranslation('biography', app()->getLocale(), $request->input('biography'));
        $productAuthor->save();

        return redirect()->route('admin.product-authors.index')->with('success', 'Product Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProductAuthor $productAuthor)
    {
        $this->authorize('delete', $productAuthor);

        // Delete associated image if exists
        if ($productAuthor->image && File::exists(public_path($productAuthor->image))) {
            File::delete(public_path($productAuthor->image));
        }

        // You might want to handle products that have this author
        // For example, set author_id to null or assign to a default author
        if (method_exists($productAuthor, 'products')) {
            $productAuthor->products()->update(['author_id' => null]);
        }

        $productAuthor->delete();

        return redirect()
            ->route('admin.product-authors.index')
            ->with('success', 'Product Author deleted successfully.');
    }
}
