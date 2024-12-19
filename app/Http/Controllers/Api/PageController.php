<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\PageWidget;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    // public function store()
    // {
    //     // $title = request()->input('title');
    //     // $slug = request()->input('slug');
    //     // $oldPage = Page::where('slug', $slug)->first();
    //     // if($oldPage){
    //     //     $slug = $slug . '-' . rand(1, 100000);
    //     // }
    //     // $page = Page::create([
    //     //     'title' => $title,
    //     //     'slug' => $slug,
    //     // ]);

    //     // return response()->json([
    //     //     'status' => 'success',
    //     //     'message' => 'Page created successfully',

    //     //     'page' => $page
    //     // ]);
    // }

    public function show($pageId)
    {
        // $page = Page::with(['pageWidgets' => function($query){
        //     $query->orderBy('position', 'asc');
        // }])->find($pageId);
        $page = Page::with(['widgets' => function($query){
            $query->orderBy('position');
        }])->find($pageId);
        // $page = Page::with('widgets')->find($pageId);

        return response()->json([
            'page' => $page
        ]);
    }

    public function update($pageId) {
        $page = Page::where('slug', request()->slug)->first();
        if($page) {
            return response()->json([
                'status' => 'error',
                'message' => 'There is another page with this slug. choose another slug.'
            ]);
        }
        $page = Page::find($pageId);
        $lang = request()->lang ?: app()->getLocale();
        $page->setTranslation('title', $lang, request()->title);
        $page->setTranslation('slug', $lang, '/' . Str::slug(request()->slug));
        $page->save();

        return response()->json([
            'status' => 'success',
            'page' => $page
        ]);
    }
    
    public function delete(Page $page) {
        return response()->json([
            'request' => request()->all()
        ]);

    }
}
