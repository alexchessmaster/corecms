<?php

namespace App\Modules\Products\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Products\Http\Requests\StoreProductTagRequest;
use App\Modules\Products\Http\Requests\UpdateProductTagRequest;
use App\Modules\Products\Http\Resources\ProductTagResource;
use App\Modules\Products\Models\ProductTag;
use App\Modules\Shared\Helpers\TranslationHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
                    return $text ?: '-Not translated- ' . TranslationHelper::firstAvailableValue($item, 'name', false);
                })
                ->editColumn('slug', function ($item) {
                    $text = $item->getTranslation('slug', app()->getLocale(), false);
                    return $text ?: '-Not translated- ' . TranslationHelper::firstAvailableValue($item, 'name', false);
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

        return view('products::product_tag.index');
    }

    public function selectTags(Request $request)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }

        $query = ProductTag::query();

        // Search functionality for Select2
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("LOWER(JSON_EXTRACT(name, '$." . app()->getLocale() . "')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("LOWER(JSON_EXTRACT(name, '$.en')) LIKE ?", ["%{$search}%"]);
            });
        }

        $tags = $query->paginate(50);

        return ProductTagResource::collection($tags);
    }

    public function create()
    {
        $this->authorize('create', ProductTag::class);

        return view('products::product_tag.create');
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

        return view('products::product_tag.edit', ['tag' => $productTag]);
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
