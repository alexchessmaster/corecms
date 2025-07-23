<?php

namespace App\Http\Controllers\Api;

use App\Models\Field;
use App\Models\Widget;
use App\Models\FieldWidget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldResource;
use App\Http\Requests\StoreFieldWidgetRequest;
use App\Http\Requests\UpdateFieldWidgetRequest;
use App\Http\Resources\FieldWidgetResource;

class FieldWidgetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $widgetId = $request->widget_id;
        $key = $request->key;
        $id = $request->id;

        $field = Field::findOrFail($id);
        $widget = Widget::findOrFail($widgetId);
        FieldWidget::updateOrCreate([
            'widget_id' => $widgetId, 'field_id' => $id, 'key' => $key
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Field created',
            'field' => new FieldWidgetResource($widget)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Widget $widget)
    {
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $widgetId, $fieldId)
    {
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $widgetId, $widgetFieldId)
    {
        Widget::findOrFail($widgetId);
        $deleted = FieldWidget::findOrFail($widgetFieldId)->delete();

        if (!$deleted) {
            return response()->json([
                'status' => 'error',
                'message' => 'Field not found or already deleted'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Field deleted',
        ], 200);
    }
}
