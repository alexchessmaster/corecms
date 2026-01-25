<?php

namespace App\Modules\News\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Modules\News\Models\News;
use App\Http\Controllers\Controller;
use App\Modules\News\Http\Resources\NewsResource;

class NewsController extends Controller
{
    public function show($bookId)
    {
        if(!empty(request()->lang)){
            app()->setLocale(request()->lang);
        }

        $book = News::withAllWidgetData()->find($bookId);

        return response()->json(NewsResource::make($book));
    }
}
