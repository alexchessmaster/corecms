<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Article;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\PageResource;

class ArticleController extends Controller
{
    public function show($articleId)
    {
        $article = Article::withAllWidgetData()->find($articleId);

        return response()->json(ArticleResource::make($article));
    }
}
