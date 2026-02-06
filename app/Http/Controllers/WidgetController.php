<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Field;
use App\Models\Widget;
use App\Modules\Shared\Helpers\FileHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\WidgetDefaultValueHelper;
use App\Http\Requests\StoreWidgetRequest;
use App\Http\Requests\UpdateWidgetRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class WidgetController extends Controller
{
    use AuthorizesRequests;

    // Display a listing of widgets
    public function index()
    {
        $this->authorize('viewAny', Widget::class);

        $widgets = Widget::all();

        return view('admin.widgets.index', compact('widgets'));
    }

    // Show the form for creating a new widget
    public function create()
    {
        $this->authorize('create', Widget::class);

        $fields = Field::all();
        $user = auth()->user();
        $authToken = $user->createToken('admin-token')->plainTextToken;

        return view('admin.widgets.create', compact('fields', 'authToken'));
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
        ]);

        $widget = new Widget($validated);

        // Handle image upload if provided
        if ($request->hasFile('image')) {
            $widget->image = FileHelper::upload($request, 'image');
        }
        $widget->user_id = auth()->id();
        $widget->save();

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

        return view('admin.widgets.edit', compact('widget', 'fieldTypes', 'authToken'));
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
