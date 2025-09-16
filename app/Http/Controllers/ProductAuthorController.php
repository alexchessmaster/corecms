<?php

namespace App\Http\Controllers;

use App\Models\ProductAuthor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProductAuthorController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductAuthor::class, 'product_author');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax() || false) {
            $data = ProductAuthor::select(['id', 'name', 'date_of_birth', 'date_of_death', 'nationality']);
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

        return view('admin.product_author.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product_author.create');
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

        $productAuthor = new ProductAuthor;

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
        return view('admin.product_author.show', compact('productAuthor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductAuthor $productAuthor)
    {
        return view('admin.product_author.edit', compact('productAuthor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductAuthor $productAuthor)
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
