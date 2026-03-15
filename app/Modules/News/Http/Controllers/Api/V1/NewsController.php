<?php

namespace App\Modules\News\Http\Controllers\Api\V1;

use App\Modules\News\Models\News;
use App\Http\Controllers\Controller;
use App\Modules\News\Http\Resources\NewsResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class NewsController extends Controller
{
    use AuthorizesRequests;

    public function show($id)
    {
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }
        $news = News::withAllWidgetData()->find($id);
        $this->authorize('view', $news);

        return response()->json(NewsResource::make($news));
    }
}
