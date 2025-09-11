<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with(['category'])->select(['id', 'title', 'slug', 'product_category_id', 'status']);

            return DataTables::of($products)
                ->editColumn('title', function ($product) {
                    $title = $product->getTranslation('title', app()->getLocale(), false);
                    return $title ?: '-Not translated-' . $product->getTranslation('title', app()->getLocale(), true);
                })
                ->addColumn('category', function ($product) {
                    return $product->category?->getTranslation('name', app()->getLocale());
                })
                ->addColumn('actions', function ($product) {
                    return '
                    <a href="' . route('admin.products.edit', $product) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="' . route('admin.products.destroy', $product) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['actions', 'name'])
                ->make(true);
        }

        return view('admin.product.index');
    }

    public function create()
    {
        $categories = ProductCategory::all();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.product.create', compact('categories', 'authToken', 'allWidgets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:product_categories,id',
            'status' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        $product = new Product;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/products');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $product->setTranslation('image', app()->getLocale(), '/uploads/products/' . $filename);
        }

        $product->setTranslation('title', app()->getLocale(), $request->input('title'));
        $product->setTranslation('description', app()->getLocale(), $request->input('description'));
        // $product->setTranslation('slug', app()->getLocale(), Str::slug($request->input('title')));
        $product->product_category_id = $request->input('category_id');
        $product->status = $request->input('status');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock') ?? 0;

        $product->save();

        return redirect()->route('admin.products.edit', [$product->id])->with('success', 'Product created successfully.');
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        $categories = ProductCategory::all();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.product.edit', compact('product', 'categories', 'authToken', 'allWidgets'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:product_categories,id',
            'status' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/products');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $product->setTranslation('image', app()->getLocale(), '/uploads/products/' . $filename);
        }

        $product->setTranslation('title', app()->getLocale(), $request->input('title'));
        $product->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        $product->setTranslation('description', app()->getLocale(), $request->input('description'));
        $product->product_category_id = $request->input('category_id');
        $product->status = $request->input('status');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock') ?? 0;

        $product->save();

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
