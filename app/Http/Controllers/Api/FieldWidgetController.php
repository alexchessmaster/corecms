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
        // $widgetId = $request->widget_id;

        // $widget = Widget::with(['fields' => function ($query) {
        //     $query->select('fields.*', 'field_widget.key');
        // }])->findOrFail($widgetId);

        // return FieldWidgetResource::collection($widget->fields);
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
        // $widget->fields()->attach($field->id, ['key' => $key]);
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
        // $widget->load(['fields' => function ($query) {
        //     $query->select('fields.*', 'field_widget.key');
        // }]);

        // return FieldWidgetResource::collection($widget->fields);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $widgetId, $fieldId)
    {
        // $pivot = FieldWidget::where('widget_id', $widgetId)
        //     ->where('field_id', $fieldId)
        //     ->firstOrFail();

        // $pivot->update([
        //     'key' => $request->key
        // ]);

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'FieldWidget updated',
        //     'field' => new FieldWidgetResource($pivot)
        // ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $widgetId, $widgetFieldId)
    {
        Widget::findOrFail($widgetId);
        $deleted = FieldWidget::findOrFail($widgetFieldId)->delete();

        // $fieldKey = $request->field_key;
        // $deleted = FieldWidget::where('widget_id', $widgetId)
        //     ->where('field_id', $fieldId)
        //     ->where('key', $fieldKey)
        //     ->delete();

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
