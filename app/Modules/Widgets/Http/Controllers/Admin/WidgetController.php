<?php

namespace App\Modules\Widgets\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Pages\Models\Page;
use App\Modules\Widgets\Models\Field;
use App\Modules\Widgets\Models\Widget;
use App\Modules\Shared\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\WidgetDefaultValueHelper;
use App\Modules\Widgets\Http\Requests\StoreWidgetRequest;
use App\Modules\Widgets\Http\Requests\UpdateWidgetRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WidgetController extends Controller
{
    use AuthorizesRequests;

    // Display a listing of widgets
    public function index()
    {
        $this->authorize('viewAny', Widget::class);

        $widgets = Widget::orderBy('order')->get();

        return view('widgets::widgets.index', compact('widgets'));
    }

    // Show the form for creating a new widget
    public function create()
    {
        $this->authorize('create', Widget::class);

        $fields = Field::all();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;
        $widgets = Widget::orderBy('order')->get();

        return view('widgets::widgets.create', compact('fields', 'authToken', 'widgets'));
    }

    // Store a newly created widget in the database
    public function store(Request $request)
    {
        $this->authorize('create', Widget::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_note' => 'nullable|string',
            'image' => 'nullable|image',
            'active' => 'required|boolean',
            'locked_fields_value' => 'required|boolean',
            'order' => 'nullable|integer',
        ]);

        $order = $request->input('order');
        if (!is_null($order)) {
            $order += 0.5;
            // dd($order);
            $widgets = Widget::orderBy('order')->get();
            $tmpWidgets = ['new' => $order];
            foreach ($widgets as $item) {
                // if($item->id === $menu->id){
                //     $item->order = $order;
                // }
                $tmpWidgets[$item->id] = $item->order;
            }
            asort($tmpWidgets);
            // dd($tmpWidgets);
            $tmpWidgets2 = [];
            foreach ($tmpWidgets as $key => $value) {
                if ($value > $order) {
                    $value++;
                }
                if ($value === $order) {
                    $tmpWidgets2[$key] = (int) ($value + 0.5);
                } else {
                    $tmpWidgets2[$key] = $value;
                }
            }
            // dd($tmpWidgets2);
            $tmpWidgets = [];
            $i = 1;
            foreach ($tmpWidgets2 as $key => $value) {
                $tmpWidgets[$key] = $i++;
            }
            foreach ($tmpWidgets as $key => $value) {
                if ($key === 'new') {
                    $widget = new Widget($validated);
                    if ($request->hasFile('image')) {
                        $widget->image = FileHelper::upload($request, 'image');
                    }
                    $widget->user_id = auth()->id();
                    $widget->save();
                } else {
                    $tmpWidget = Widget::find($key);
                    $tmpWidget->order = $value;
                    $tmpWidget->save();
                }
            }
        }

        return redirect()->route('admin.widgets.edit', $widget->id)->with('success', 'Widget created successfully.');
    }

    // Show the form for editing the specified widget
    public function edit($id)
    {
        $widget = Widget::findOrFail($id);
        $this->authorize('view', $widget);

        $fieldTypes = Field::get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;
        $widgets = Widget::orderBy('order')->get();

        return view('widgets::widgets.edit', compact('widget', 'widgets', 'fieldTypes', 'authToken'));
    }

    // Update the specified widget in the database
    public function update(Request $request, $id)
    {
        $widget = Widget::findOrFail($id);
        $this->authorize('update', $widget);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_note' => 'nullable|string',
            'image' => 'nullable|image',
            'active' => 'required|boolean',
            'locked_fields_value' => 'required|boolean',
        ]);

        // Handle order
        $order = $request->input('order');
        if (!is_null($order)) {
            $order += 0.5;
            // dd($order);
            $widgets = Widget::orderBy('order')->get();
            $tmpWidgets = [];
            foreach ($widgets as $item) {
                if ($item->id === $widget->id) {
                    $item->order = $order;
                }
                $tmpWidgets[$item->id] = $item->order;
            }
            asort($tmpWidgets);
            $tmpWidgets2 = [];
            foreach ($tmpWidgets as $key => $value) {
                if ($value > $order) {
                    $value++;
                }
                if ($value === $order) {
                    $tmpWidgets2[$key] = (int) ($value + 0.5);
                } else {
                    $tmpWidgets2[$key] = $value;
                }
            }
            $tmpWidgets = [];
            $i = 1;
            foreach ($tmpWidgets2 as $key => $value) {
                $tmpWidgets[$key] = $i++;
            }
            foreach ($tmpWidgets as $key => $value) {
                $tmpWidget = Widget::find($key);
                $tmpWidget->order = $value;
                $tmpWidget->save();
            }
        }
        // End handle order

        $widget->fill($validated);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $widget->image = FileHelper::upload($request, 'image');
        }
        $widget->user_id = auth()->id();
        $widget->save();

        return redirect()->route('admin.widgets.index')->with('success', 'Widget updated successfully.');
    }

    // Remove the specified widget from the database
    public function destroy($id)
    {
        $widget = Widget::findOrFail($id);
        $this->authorize('delete', $widget);

        $widget->delete();

        return redirect()->route('admin.widgets.index')->with('success', 'Widget deleted successfully.');
    }
}
