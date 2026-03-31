<?php

namespace App\Modules\News\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\News\Http\Resources\NewsResource;
use App\Modules\News\Models\News;
use App\Modules\Shared\Jobs\GenerateSitemapsJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsController extends Controller
{
    use AuthorizesRequests;

    public function show($id)
    {
        if (!empty(request()->lang)) {
            app()->setLocale(request()->lang);
        }
        $news = News::withAllWidgetData()->find($id);
        $this->authorize('view', $news);

        return response()->json(NewsResource::make($news));
    }

    public function removeNewsLanguage($id, $lang)
    {
        $news = News::findOrFail($id);
        $this->authorize('edit', $news);
        $titleTranslations = $news->getTranslations('title');
        if (count($titleTranslations) <= 1) {
            // It has only one language.
            $this->authorize('delete', $news);
            $news->delete();
            GenerateSitemapsJob::dispatch();

            return response()->json([
                'message' => "News deleted successfully.",
                'status' => 'item-deleted',
            ]);
        }
        $titleTranslation = $news->getTranslation('title', $lang, false);
        $slugTranslation = $news->getTranslation('slug', $lang, false);
        $langExist = false;
        if (!empty($titleTranslation)) {
            $langExist = true;
            $news->forgetTranslation('title', $lang);
            $news->save();
        }
        if (!empty($slugTranslation)) {
            $langExist = true;
            $news->forgetTranslation('slug', $lang);
            $news->save();
        }
        if (! $langExist) {
            return response()->json([
                'message' => "News doesn't have $lang language.",
                'status' => 'error',
            ], 404);
        }
        if (!empty($slugTranslation)) {
            $news->forgetTranslation('slug', $lang);
            $news->saveQuietly();
        }

        GenerateSitemapsJob::dispatch();

        return response()->json([
            'message' => "language $lang removed successfully",
            'status' => 'success',
        ]);
    }
}
