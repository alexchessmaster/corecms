<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Page;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['category', 'tags'])->get();
        return view('admin.article.index', compact('articles'));
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
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'template_page_id' => 'required|exists:pages,id',
        ]);

        $article = new Article;
        
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
        $article->category_id = $request->input('category_id');
        $article->template_page_id = $request->input('template_page_id');
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
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
            'template_page_id' => 'required|exists:pages,id',
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
        $article->category_id = $request->input('category_id');
        $article->template_page_id = $request->input('template_page_id');
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
