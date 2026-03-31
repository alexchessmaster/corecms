<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Modules\Shared\Jobs\GenerateSitemapsJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ArticleController extends Controller
{
    use AuthorizesRequests;
    
    public function show($articleId)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }
        $article = Article::withAllWidgetData()->find($articleId);
        $this->authorize('view', $article);

        return response()->json(ArticleResource::make($article));
    }

    public function removeArticleLanguage($id, $lang)
    {
        $article = Article::findOrFail($id);
        $this->authorize('edit', $article);
        $titleTranslations = $article->getTranslations('title');
        if (count($titleTranslations) <= 1) {
            // It has only one language.
            $this->authorize('delete', $article);
            $article->delete();
            GenerateSitemapsJob::dispatch();

            return response()->json([
                'message' => "Article deleted successfully.",
                'status' => 'item-deleted',
            ]);
        }
        $titleTranslation = $article->getTranslation('title', $lang, false);
        $slugTranslation = $article->getTranslation('slug', $lang, false);
        $langExist = false;
        if (!empty($titleTranslation)) {
            $langExist = true;
            $article->forgetTranslation('title', $lang);
            $article->save();
        }
        if (!empty($slugTranslation)) {
            $langExist = true;
            $article->forgetTranslation('slug', $lang);
            $article->save();
        }
        if (! $langExist) {
            return response()->json([
                'message' => "Article doesn't have $lang language.",
                'status' => 'error',
            ], 404);
        }
        if (!empty($slugTranslation)) {
            $article->forgetTranslation('slug', $lang);
            $article->saveQuietly();
        }

        GenerateSitemapsJob::dispatch();

        return response()->json([
            'message' => "language $lang removed successfully",
            'status' => 'success',
        ]);
    }
}
