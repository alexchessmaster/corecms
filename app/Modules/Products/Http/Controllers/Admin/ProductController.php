<?php

namespace App\Modules\Products\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Products\Models\Product;
use App\Modules\Products\Models\ProductCategory;
use App\Modules\Shared\Helpers\StrHelper;
use App\Modules\Shared\Jobs\GenerateSitemapsJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Product::class);

        if ($request->ajax()) {
            $products = Product::visibleTo(auth()->user())->with(['category'])->select(['id', 'title', 'slug', 'product_category_id', 'status', 'updated_at', 'scheduled_at']);

            return DataTables::of($products)
                ->editColumn('title', function ($product) {
                    $title = $product->getTranslation('title', app()->getLocale(), false);
                    return $title ?: '-Not translated-' . $product->getTranslation('title', app()->getLocale(), true);
                })
                ->addColumn('category', function ($product) {
                    return $product->category->getTranslation('name', app()->getLocale(), false);
                })
                ->addColumn('date', function ($item) {
                    return match($item->status){
                        'scheduled' => '<span class="badge bg-info text-dark">Scheduled at:</span>' . $item->scheduled_at,
                        'draft' => '<span class="badge bg-warning text-dark">Draft</span>' . $item->scheduled_at,
                        default => '<span class="badge bg-success text-dark">Updated at:</span>' . $item->updated_at,
                    };
                })
                ->addColumn('translated_languages', function ($product) {
                    $translations = $product->getTranslations('title');
                    $keys = array_keys($translations);
                    sort($keys);
                    return implode(' - ', $keys);
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
                ->rawColumns(['actions', 'date'])
                ->make(true);
        }

        return view('products::product.index');
    }

    public function create()
    {
        $this->authorize('create', Product::class);

        $categories = ProductCategory::all();
        $allWidgets = Widget::where('active', true)->orderBy('order')->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('products::product.create', compact('categories', 'authToken', 'allWidgets'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:product_categories,id',
            'status' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'author_id' => 'nullable|int',
            'tag_ids' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'sitemap_exclude' => 'nullable',
        ]);

        $product = new Product;
        $product->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $folderName = 'products';
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path("uploads/$folderName");
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $product->setTranslation('image', app()->getLocale(), "/uploads/$folderName/" . $filename);
        }

        $product->setTranslation('title', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('title')));
        if (!empty($request->slug)) {
            $product->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }
        $product->setTranslation('description', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('description')));
        $product->product_category_id = $request->input('category_id');
        $product->status = $request->input('status');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock') ?? 0;
        $product->author_id = $request->input('author_id') ?? null;

        // Sitemap
        if (!empty($request->input('sitemap_exclude'))) {
            $product->sitemap_exclude = true;
        } else {
            $product->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $product->sitemap_priority = $request->input('sitemap_priority');
        } else {
            $product->sitemap_priority = null;
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $product->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        } else {
            $product->sitemap_change_frequency = null;
        }
        // End sitemap

        $product->save();

        // Sync tags if provided after save
        $product->tags()->sync($request->input('tag_ids', []));

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->route('admin.products.edit', [$product->id])->with('success', 'Product created successfully.');
    }

    public function edit($productId)
    {
        $product = Product::findOrFail($productId);
        $this->authorize('update', $product);

        $categories = ProductCategory::all();
        $allWidgets = Widget::where('active', true)->orderBy('order')->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('products::product.edit', compact('product', 'categories', 'authToken', 'allWidgets'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:product_categories,id',
            'status' => 'required|string',
            'price' => 'nullable|numeric|min:0',
            'stock' => 'nullable|integer|min:0',
            'author_id' => 'nullable|int',
            'tag_ids' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'sitemap_exclude' => 'nullable',
        ]);

        $product->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $folderName = 'products';
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path("uploads/$folderName");
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $product->setTranslation('image', app()->getLocale(), "/uploads/$folderName/" . $filename);
        }

        $product->setTranslation('title', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('title')));
        if ($request->slug !== $product->getTranslation('slug', app()->getLocale())) {
            $product->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }
        $product->setTranslation('description', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('description')));
        $product->product_category_id = $request->input('category_id');
        $product->status = $request->input('status');
        $product->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $product->price = $request->input('price');
        $product->stock = $request->input('stock') ?? 0;
        $product->author_id = $request->input('author_id') ?: null;

        // Sitemap
        if (!empty($request->input('sitemap_exclude'))) {
            $product->sitemap_exclude = true;
        } else {
            $product->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $product->sitemap_priority = $request->input('sitemap_priority');
        } else {
            $product->sitemap_priority = null;
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $product->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        } else {
            $product->sitemap_change_frequency = null;
        }
        // End sitemap

        $product->save();

        // Sync tags if provided after save
        $product->tags()->sync($request->input('tag_ids', []));

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
