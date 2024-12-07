<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use App\Models\Setting;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Http\Resources\SettingResource;
use App\Http\Resources\LanguageResource;

class CommonDataController extends Controller
{
    public function index()
    {
        // $settings = Setting::all();
        // $languages = Language::all();
        // $menus = Menu::all();
        
        // return response()->json([
        //     'data' => [
        //         'settings' => SettingResource::collection($settings),
        //         'languages' => LanguageResource::collection($languages),
        //         // 'menus' => MenuResource::collection($menus),
        //     ]
        // ]);
    }
}
