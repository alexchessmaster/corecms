<?php

namespace App\Modules\Articles\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\Category;
use App\Modules\Pages\Models\Page;
use App\Modules\Articles\Models\Tag;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Shared\Helpers\StrHelper;
use App\Modules\Shared\Jobs\GenerateSitemapsJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use function App\Http\Controllers\setTranslation;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Article::class);

        if ($request->ajax()) {
            $articles = Article::visibleTo(auth()->user())->select(['id', 'title', 'slug', 'category_id', 'status'])->with(['category', 'tags']);

            return DataTables::of($articles)
                ->editColumn('title', function ($article) {
                    $title = $article->getTranslation('title', app()->getLocale(), false);
                    return $title ?: '-Not translated-' . $article->getTranslation('title', app()->getLocale(), true);
                })
                ->addColumn('category', function ($article) {
                    return $article->category->getTranslation('name', app()->getLocale(), false);
                })
                ->addColumn('tags', function ($article) {
                    return $article->tags->map(function ($tag) {
                        return '<span class="badge bg-info text-dark">' . $tag->getTranslation('name', app()->getLocale()) . '</span>';
                    })->implode(' ');
                })
                ->addColumn('translated_languages', function ($tag) {
                    $translations = $tag->getTranslations('title');
                    $keys = array_keys($translations);
                    sort($keys);
                    return implode(' - ', $keys);
                })
                ->addColumn('actions', function ($article) {
                    return '
                    <a href="' . route('admin.articles.edit', $article) . '" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form action="' . route('admin.articles.destroy', $article) . '" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['categories', 'tags', 'actions', 'title'])
                ->make(true);
        }

        return view('articles::article.index');
    }

    public function create()
    {
        $this->authorize('create', Article::class);

        $categories = Category::all();
        $tags = Tag::all();
        $allWidgets = Widget::where('active', true)->orderBy('order')->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('articles::article.create', compact('categories', 'tags', 'allWidgets', 'authToken'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Article::class);

        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:categories,id',
            'tag_ids' => 'nullable',
            'author_id' => 'nullable|int',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
            'status' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);

        $article = new Article;
        $article->user_id = auth()->id();
        if ($request->hasFile('image')) {
            $folderName = 'articles';
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path("uploads/$folderName");
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
                chown($destinationPath, 'www-data');
                chgrp($destinationPath, 'www-data');
            }
            $image->move($destinationPath, $filename);
            $article->setTranslation('image', app()->getLocale(), "/uploads/$folderName/" . $filename);
        }

        $article->setTranslation('title', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('title')));
        if (!empty($request->slug)) {
            $article->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }
        $article->setTranslation('description', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('description')));
        $article->category_id = $request->input('category_id');

        $article->author_id = $request->input('author_id') ?: null;

        // Sitemap
        if (!empty($request->input('sitemap_exclude'))) {
            $article->sitemap_exclude = true;
        } else {
            $article->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $article->sitemap_priority = $request->input('sitemap_priority');
        } else {
            $article->sitemap_priority = null;
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $article->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        } else {
            $article->sitemap_change_frequency = null;
        }
        // End sitemap

        if (!empty($request->input('primary_language'))) {
            $article->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $article->primary_language = null;
            }
        }
        $article->status = request()->status;
        $article->scheduled_at = request()->scheduled_at ? \Carbon\Carbon::parse(request()->scheduled_at) : null;

        $article->save();

        // Sync tags if provided after save
        $article->tags()->sync($request->input('tag_ids', []));

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->route('admin.articles.edit', [$article->id])->with('success', 'Article created successfully.');
    }

    public function edit($articleId)
    {
        $article = Article::withAllWidgetData()->findOrFail($articleId);
        $this->authorize('update', $article);

        $categories = Category::all();
        $tags = Tag::all();
        $allWidgets = Widget::where('active', true)->orderBy('order')->get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('articles::article.edit', compact('article', 'categories', 'tags', 'allWidgets', 'authToken'));
    }

    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1500',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|string',
            'author_id' => 'nullable|int',
            'scheduled_at' => 'nullable|date',
            'tag_ids' => 'nullable',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
            'primary_language' => 'nullable|string',
        ]);
        $article->user_id = auth()->id();

        if ($request->hasFile('image')) {
            $folderName = 'articles';
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('uploads/articles');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $article->setTranslation('image', app()->getLocale(), "/uploads/$folderName/" . $filename);
        }

        $article->setTranslation('title', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('title')));
        if ($request->slug !== $article->getTranslation('slug', app()->getLocale())) {
            $article->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        }
        $article->setTranslation('description', app()->getLocale(), StrHelper::removeUnicodeCharacters($request->input('description')));
        $article->category_id = $request->input('category_id');
        $article->author_id = $request->input('author_id') ?: null;

        // Sitemap
        if (!empty($request->input('sitemap_exclude'))) {
            $article->sitemap_exclude = true;
        } else {
            $article->sitemap_exclude = null;
        }
        if (!empty($request->input('sitemap_priority'))) {
            $article->sitemap_priority = $request->input('sitemap_priority');
        } else {
            $article->sitemap_priority = null;
        }
        if (!empty($request->input('sitemap_change_frequency'))) {
            $article->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        } else {
            $article->sitemap_change_frequency = null;
        }
        // End sitemap

        if (!empty($request->input('primary_language'))) {
            $article->primary_language = $request->input('primary_language');
            if ($request->input('primary_language') === 'default') {
                $article->primary_language = null;
            }
        }
        $article->status = request()->input('status');
        $article->scheduled_at = request()->input('scheduled_at') ? \Carbon\Carbon::parse(request()->scheduled_at) : null;

        $article->save();

        // Sync tags if provided after save
        $article->tags()->sync($request->input('tag_ids', []));

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->back()->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $this->authorize('delete', $article);

        $article->delete();

        // Dispatch sitemap regeneration to queue.
        GenerateSitemapsJob::dispatch();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }
}
