<?php

namespace App\Modules\News\Http\Controllers\Admin;

use App\Models\Widget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Modules\News\Models\News;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\News\Models\NewsCategory;
use App\Modules\Shared\Helpers\StrHelper;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', News::class);

        if ($request->ajax()) {
            $news = News::visibleTo(auth()->user())->with(['category'])->select(['id', 'title', 'slug', 'news_category_id', 'status']);

            return DataTables::of($news)
                ->editColumn('title', function ($news) {
                    $title = $news->getTranslation('title', app()->getLocale(), false);
                    return $title ?: '-Not translated-' . $news->getTranslation('title', app()->getLocale(), true);
                })
                ->addColumn('category', function ($news) {
                    return $news->category?->getTranslation('name', app()->getLocale());
                })
                ->addColumn('translated_languages', function ($news) {
                    $translations = $news->getTranslations('title');
                    $keys = array_keys($translations);
                    sort($keys);
                    return implode(' - ', $keys);
                })
                ->addColumn('actions', function ($news) {
                    return '
                    <a href="' . route('admin.news.edit', $news) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="' . route('admin.news.destroy', $news) . '" method="POST" style="display:inline;">
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

        return view('news::news.index');
    }

    public function create()
    {
        $this->authorize('create', News::class);

        $categories = NewsCategory::all();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('news::news.create', compact('categories', 'authToken', 'allWidgets'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', News::class);

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,webp,gif,svg,avif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:news_categories,id',
            'status' => 'required|string',
            'author_id' => 'nullable|int',
            'tag_ids' => 'nullable',
            'scheduled_at' => 'nullable|date',
            'news_date' => 'nullable|date',
        ]);

        $news = new News;
        $news->user_id = auth()->id();

        $folderName = 'news';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '-' . $image->getClientOriginalName();
            $destinationPath = public_path("uploads/$folderName");
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $news->setTranslation('image', app()->getLocale(), "/uploads/$folderName/" . $filename);
        }

        $news->setTranslation('title', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('title')));
        if(!empty($request->slug)){
            $news->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }
        $news->setTranslation('description', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('description')));
        $news->news_category_id = $request->input('category_id');
        $news->status = $request->input('status');
        $news->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $news->news_date = request()->news_date ? \Carbon\Carbon::parse(request()->news_date) : null;
        $news->created_at = request()->news_date ? \Carbon\Carbon::parse(request()->news_date) : now();

        if ($request->author_id) {
            $news->author_id = $request->author_id;
        }

        $news->save();

        // Sync tags if provided after save
        if ($request->has('tag_ids')) {
            $news->tags()->sync($request->input('tag_ids', []));
        }

        // event(new NewsCreatedOrUpdatedEvent);

        return redirect()->route('admin.news.edit', [$news->id])->with('success', 'News created successfully.');
    }

    public function edit($newsId)
    {
        $news = News::findOrFail($newsId);
        $this->authorize('update', $news);

        $categories = NewsCategory::all();
        $allWidgets = Widget::where('active', true)->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('news::news.edit', compact('news', 'categories', 'authToken', 'allWidgets'));
    }

    public function update(Request $request, News $news)
    {
        $this->authorize('update', $news);

        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,webp,gif,svg,avif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:news_categories,id',
            'status' => 'required|string',
            'author_id' => 'nullable|int',
            'tag_ids' => 'nullable',
            'scheduled_at' => 'nullable|date',
            'news_date' => 'nullable|date',
        ]);

        $news->user_id = auth()->id();

        $folderName = 'news';

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '-' . $image->getClientOriginalName();
            $destinationPath = public_path("uploads/$folderName");
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $news->setTranslation('image', app()->getLocale(), "/uploads/$folderName/" . $filename);
        }
        $news->setTranslation('title', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('title')));
        if($request->slug !== $news->getTranslation('slug', app()->getLocale())){
            $news->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }
        $news->setTranslation('description', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('description')));
        $news->news_category_id = $request->input('category_id');
        $news->status = $request->input('status');
        $news->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;
        $news->news_date = request()->news_date ? \Carbon\Carbon::parse(request()->news_date) : null;
        $news->created_at = request()->news_date ? \Carbon\Carbon::parse(request()->news_date) : now();
        if ($request->author_id) {
            $news->author_id = $request->author_id;
        }

        $news->save();

        // Sync tags if provided after save
        if ($request->has('tag_ids')) {
            $news->tags()->sync($request->input('tag_ids', []));
        }

        return redirect()->back()->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        $this->authorize('delete', $news);

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'News deleted successfully.');
    }
}
