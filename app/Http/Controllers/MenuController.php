<?php

namespace App\Http\Controllers;

use App\Helpers\FileHelper;
use App\Models\Menu;
use Illuminate\Support\Facades\View;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Language;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::get();

        return view('admin.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::orderBy('order')->get();

        return view('admin.menu.create', compact('menus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $order = $request->input('order');
        if (!is_null($order)) {
            $order += 0.5;
            // dd($order);
            $menus = Menu::orderBy('order')->get();
            $tmpMenus = ['new' => $order];
            foreach ($menus as $item) {
                // if($item->id === $menu->id){
                //     $item->order = $order;
                // }
                $tmpMenus[$item->id] = $item->order;
            }
            asort($tmpMenus);
            // dd($tmpMenus);
            $tmpMenus2 = [];
            foreach ($tmpMenus as $key => $value) {
                if ($value > $order) {
                    $value++;
                }
                if ($value === $order) {
                    $tmpMenus2[$key] = (int) ($value + 0.5);
                } else {
                    $tmpMenus2[$key] = $value;
                }
            }
            // dd($tmpMenus2);
            $tmpMenus = [];
            $i = 1;
            foreach ($tmpMenus2 as $key => $value) {
                $tmpMenus[$key] = $i++;
            }
            foreach ($tmpMenus as $key => $value) {
                if ($key === 'new') {
                    Menu::create([
                        'name' => $request->input('name'),
                        'link' => $request->input('link'),
                        'image' => FileHelper::upload($request, 'image'),
                        'image_alt' => $request->input('image_alt'),
                        'description' => $request->input('description'),
                        'parent_id' => $request->input('parent_id'),
                        'order' => $value
                    ]);
                } else {
                    $tmpMenu = Menu::find($key);
                    $tmpMenu->order = $value;
                    $tmpMenu->save();
                }
            }
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $menus = Menu::orderBy('order')->get();

        return view('admin.menu.edit', compact('menus', 'menu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        // if($request->input('lang')){
        //     \App::setLocale($request->input('lang'));
        // }
        // dd($request->all());
        $order = $request->input('order');
        if (!is_null($order)) {
            $order += 0.5;
            // dd($order);
            $menus = Menu::orderBy('order')->get();
            $tmpMenus = [];
            foreach ($menus as $item) {
                if ($item->id === $menu->id) {
                    $item->order = $order;
                }
                $tmpMenus[$item->id] = $item->order;
            }
            asort($tmpMenus);
            $tmpMenus2 = [];
            foreach ($tmpMenus as $key => $value) {
                if ($value > $order) {
                    $value++;
                }
                if ($value === $order) {
                    $tmpMenus2[$key] = (int) ($value + 0.5);
                } else {
                    $tmpMenus2[$key] = $value;
                }
            }
            $tmpMenus = [];
            $i = 1;
            foreach ($tmpMenus2 as $key => $value) {
                $tmpMenus[$key] = $i++;
            }
            foreach ($tmpMenus as $key => $value) {
                $tmpMenu = Menu::find($key);
                $tmpMenu->order = $value;
                $tmpMenu->save();
            }
        }
        $menu->name = $request->input('name');
        $menu->link = $request->input('link');
        $menu->parent_id = $request->input('parent_id');
        if ($request->hasFile('image')) {
            $menu->image = FileHelper::upload($request, 'image');
        }
        $menu->image_alt = $request->input('image_alt');
        $menu->description = $request->input('description');
        $menu->save();

        return redirect()->back()->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()->back();
    }
}
