<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Field;
use App\Models\Widget;
use App\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\WidgetDefaultValueHelper;
use App\Http\Requests\StoreWidgetRequest;
use App\Http\Requests\UpdateWidgetRequest;

class WidgetController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Widget::class, 'widget');
    }

    // Display a listing of widgets
    public function index()
    {
        $widgets = Widget::all();

        return view('admin.widgets.index', compact('widgets'));
    }

    // Show the form for creating a new widget
    public function create()
    {
        $fields = Field::all();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.widgets.create', compact('fields', 'authToken'));
    }

    // Store a newly created widget in the database
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_note' => 'nullable|string',
            'image' => 'nullable|image',
            'active' => 'required|boolean',
            'locked_fields_value' => 'required|boolean',
        ]);

        $widget = new Widget($validated);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $widget->image = FileHelper::upload($request, 'image');
        }

        $widget->save();

        return redirect()->route('admin.widgets.edit', $widget->id)->with('success', 'Widget created successfully.');
    }

    // Show the form for editing the specified widget
    public function edit($id)
    {
        $widget = Widget::findOrFail($id);
        $fieldTypes = Field::get();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.widgets.edit', compact('widget', 'fieldTypes', 'authToken'));
    }

    // Update the specified widget in the database
    public function update(Request $request, $id)
    {
        $widget = Widget::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'user_note' => 'nullable|string',
            'image' => 'nullable|image',
            'active' => 'required|boolean',
            'locked_fields_value' => 'required|boolean',
        ]);

        $widget->fill($validated);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $widget->image = FileHelper::upload($request, 'image');
        }

        $widget->save();

        return redirect()->route('admin.widgets.index')->with('success', 'Widget updated successfully.');
    }

    // Remove the specified widget from the database
    public function destroy($id)
    {
        $widget = Widget::findOrFail($id);
        $widget->delete();

        return redirect()->route('admin.widgets.index')->with('success', 'Widget deleted successfully.');
    }
}
