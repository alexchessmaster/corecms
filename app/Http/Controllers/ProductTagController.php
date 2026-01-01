<?php

namespace App\Http\Controllers;

use App\Models\ProductTag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductTagRequest;
use App\Http\Requests\UpdateProductTagRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProductTagController extends Controller
{
    use AuthorizesRequests;
    
    public function index(Request $request)
    {
        $this->authorize('viewAny', ProductTag::class);

        if ($request->ajax()) {
            $data = ProductTag::visibleTo(auth()->user())->select(['id', 'name', 'slug']);
            return datatables()
                ->of($data)
                ->editColumn('name', function ($item) {
                    $text = $item->getTranslation('name', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('name', app()->getLocale(), true);
                })
                ->editColumn('slug', function ($item) {
                    $text = $item->getTranslation('slug', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . $item->getTranslation('slug', app()->getLocale(), true);
                })
                ->addColumn('actions', function ($row) {
                    $editUrl = route('admin.product-tags.edit', $row->id);
                    $deleteUrl = route('admin.product-tags.destroy', $row->id);
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

        return view('admin.product_tag.index');
    }

    public function create()
    {
        $this->authorize('create', ProductTag::class);
        
        return view('admin.product_tag.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', ProductTag::class);
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $productTag = new ProductTag;
        $productTag->user_id = auth()->id();
        $productTag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $productTag->setTranslation('slug', app()->getLocale(), Str::slug($request->input('name')));
        $productTag->save();

        return redirect()->route('admin.product-tags.index')->with('success', 'Tag created successfully.');
    }

    public function edit(ProductTag $productTag)
    {
        $this->authorize('update', $productTag);
        
        return view('admin.product_tag.edit', ['tag' => $productTag]);
    }

    public function update(Request $request, ProductTag $productTag)
    {
        $this->authorize('update', $productTag);
        
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $productTag->user_id = auth()->id();
        $productTag->setTranslation('name', app()->getLocale(), $request->input('name'));
        $productTag->setTranslation('slug', app()->getLocale(), Str::slug($request->input('name')));

        $productTag->save();

        return redirect()->route('admin.product-tags.index')->with('success', 'Tag updated successfully.');
    }

    public function destroy(ProductTag $productTag)
    {
        $this->authorize('delete', $productTag);
        
        $productTag->delete();
        return redirect()->route('admin.product-tags.index')->with('success', 'Tag deleted successfully.');
    }
}
