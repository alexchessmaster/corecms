<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Page;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $articles = Article::with(['category', 'tags']);

            return DataTables::of($articles)
                ->editColumn('title', function ($article) {
                    $title = $article->getTranslation('title', app()->getLocale(), false);
                    return $title ?: '<em class="text-danger">-Not translated-</em>';
                })
                ->addColumn('category', function ($article) {
                    return $article->category->getTranslation('name', app()->getLocale());
                })
                ->addColumn('tags', function ($article) {
                    return $article->tags->map(function ($tag) {
                        return '<span class="badge bg-info text-dark">' . $tag->getTranslation('name', app()->getLocale()) . '</span>';
                    })->implode(' ');
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
                ->rawColumns(['tags', 'actions', 'title'])
                ->make(true);
        }

        return view('admin.article.index');
    }

    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
        $pages = Page::where('type', 'template')->get();

        return view('admin.article.create', compact('categories', 'tags', 'pages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'template_page_id' => 'required|exists:pages,id',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
        ]);

        $article = new Article;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/articles');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true);
            }
            $image->move($destinationPath, $filename);
            $article->image = '/uploads/articles/' . $filename;
        }

        $article->setTranslation('title', app()->getLocale(), $request->input('title'));
        $article->setTranslation('description', app()->getLocale(), $request->input('description'));
        $article->setTranslation('content', app()->getLocale(), $request->input('content'));
        $article->category_id = $request->input('category_id');
        $article->template_page_id = $request->input('template_page_id');
        if(!empty($request->input('sitemap_exclude'))){
            $article->sitemap_exclude = true;
        } else {
            $article->sitemap_exclude = null;
        }
        if(!empty($request->input('sitemap_priority'))){
            $article->sitemap_priority = $request->input('sitemap_priority');
        }
        if(!empty($request->input('sitemap_change_frequency'))){
            $article->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        $article->save();
        $article->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $categories = Category::all();
        $tags = Tag::all();
        $pages = Page::where('type', 'template')->get();

        return view('admin.article.edit', compact('article', 'categories', 'tags', 'pages'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'image' => 'nullable|mimes:jpg,jpeg,png,webm,gif|max:5000',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string|max:1500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'template_page_id' => 'required|exists:pages,id',
            'sitemap_exclude' => 'nullable',
            'sitemap_priority' => 'nullable',
            'sitemap_change_frequency' => 'nullable',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $destinationPath = public_path('uploads/articles');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $image->move($destinationPath, $filename);
            $article->image = '/uploads/articles/' . $filename;
        }

        $article->setTranslation('title', app()->getLocale(), $request->input('title'));
        $article->setTranslation('content', app()->getLocale(), $request->input('content'));
        $article->setTranslation('slug', app()->getLocale(), $request->input('slug'));
        $article->setTranslation('description', app()->getLocale(), $request->input('description'));
        $article->category_id = $request->input('category_id');
        $article->template_page_id = $request->input('template_page_id');
        if(!empty($request->input('sitemap_exclude'))){
            $article->sitemap_exclude = true;
        } else {
            $article->sitemap_exclude = null;
        }
        if(!empty($request->input('sitemap_priority'))){
            $article->sitemap_priority = $request->input('sitemap_priority');
        }
        if(!empty($request->input('sitemap_change_frequency'))){
            $article->sitemap_change_frequency = $request->input('sitemap_change_frequency');
        }
        $article->save();
        $article->tags()->sync($request->input('tags', []));

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully.');
    }
}
