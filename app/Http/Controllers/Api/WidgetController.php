<?php

namespace App\Http\Controllers\Api;

use stdClass;
use App\Models\Page;
use App\Models\Widget;
use App\Models\FieldValue;
use App\Models\PageWidget;
use App\Models\Widgetable;
use App\Models\FieldWidget;
use Illuminate\Http\Request;
use App\Models\WidgetFieldValues;
use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetResource;
use App\Http\Resources\PageWidgetResource;
use App\Http\Resources\FieldWithValueResource;

class WidgetController extends Controller
{
    public function show($id)
    {
        $widget = Widget::with('fieldWidgets.field')->find($id);

        return response()->json(new WidgetResource($widget));
    }

    public function attach()
    {
        $widgetId = request()->input('widgetId');
        $widgetableId = request()->input('widgetableId');
        $widgetableType = request()->input('widgetableType');
        $addWidgetPosition = request()->input('addWidgetPosition');
        
        // Find the widget
        $widget = Widget::findOrFail($widgetId);
        if (! $widget) {
            return response()->json(['status' => 'error', 'message' => 'Widget not found', 'request' => request()->all()]);
        }
        // : fix this part  [2025-07-28 22:05:01] local.ERROR: Illegal operator and value combination. {"exception":"[object] (InvalidArgumentException(code: 0): Illegal operator and value combination. at /home/alex/azadandish.net_backend/vendor/laravel/framework/src/Illuminate/Database/Query/Builder.php:956)
        //        #2 /home/alex/azadandish.net_backend/app/Http/Controllers/Api/WidgetController.php(43): Illuminate\\Database\\Eloquent\\Builder->where()
        // try this with postman
        /*
        route: patch
        https://backend.azadandish.net/api/widgets/attach

        payload:
         {
            "widgetableId": "3",
            "widgetableType": "Book",
            "widgetId": "1",
            "addWidgetPosition": null
        }
         */
        // dd(Widgetable::where('widgetable_id', $widgetableId)
        //     ->where('widgetable_type', 'App\Models\\' . $widgetableType)
        //     ->where('position', '>=', $addWidgetPosition)
        //     ->orderBy('position')
        //     ->get());
        //
        // FIXED: Now I don't know why it was not working before, but now it works.
        $widgetables = Widgetable::where('widgetable_id', $widgetableId)
            ->where('widgetable_type', 'App\Models\\' . $widgetableType)
            ->where('position', '>=', (int)$addWidgetPosition)
            ->orderBy('position')
            ->get();
        foreach ($widgetables as $widgetable) {
            $widgetable->increment('position');
        }

        $created = Widgetable::create([
            'widget_id' => $widgetId,
            'widgetable_id' => $widgetableId,
            'widgetable_type' => 'App\Models\\' . $widgetableType,
            'position' => $addWidgetPosition,
        ]);

        // This function part is not necessary
        // Update positions for all widgetables if the db has wrong positions
        // Just in case the positions are not correct. (if server got restarted during the previous loop)
        $i = 0;
        $widgetables = Widgetable::where('widgetable_id', $widgetableId)
            ->where('widgetable_type', 'App\Models\\' . $widgetableType)
            ->orderBy('position')
            ->get();
        foreach ($widgetables as $widgetable) {
            $widgetable->position = $i;
            $widgetable->save();
            $i++;
        }

        // fix the bug related to to the getting value of the fields of the newly attached widget
        // The bug appear after I wanted
        // Find all FieldWidgets (fields attached to this widget)
        $fieldWidgets = FieldWidget::where('widget_id', $widget->id)->get();
        foreach ($fieldWidgets as $fieldWidget) {
            $widgetFieldValue = new WidgetFieldValues;
            $widgetFieldValue->widgetable_id = $created->id; // the new Widgetable we just attached
            $widgetFieldValue->field_widget_id = $fieldWidget->id;
            $widgetFieldValue->value = null; // initialize as null
            $widgetFieldValue->save();
        }

        if ($widget->locked_fields_value) {
            // Find all FieldWidgets (fields attached to this widget)
            $fieldWidgets = FieldWidget::where('widget_id', $widget->id)->get();

            foreach ($fieldWidgets as $fieldWidget) {
                $widgetFieldValue = new WidgetFieldValues;
                $widgetFieldValue->widgetable_id = $created->id; // the new Widgetable we just attached
                $widgetFieldValue->field_widget_id = $fieldWidget->id;

                // Attempt to copy from another instance if it exists
                $existingWidgetFieldValue = WidgetFieldValues::whereIn(
                    'widgetable_id',
                    Widgetable::where('widget_id', $widget->id)
                        ->where('id', '<>', $created->id) // exclude current
                        ->pluck('id')
                )
                    ->where('field_widget_id', $fieldWidget->id)
                    ->first();

                if ($existingWidgetFieldValue) {
                    $widgetFieldValue->value = $existingWidgetFieldValue->value;
                } else {
                    $widgetFieldValue->value = null; // no previous value, initialize as null
                }

                $widgetFieldValue->save();
            }
        }

        if (!$created) {
            return response()->json(['status' => 'error', 'message' => 'Widget could not be attached']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Widget attached successfully',
            'widget' => $widget,
        ]);
    }

    public function detach()
    {
        $widgetableId = request()->input('widgetableId');
        $widgetableType = request()->input('widgetableType');
        $position = request()->input('positionId');

        // Find the model (Page, Article, or Category)

        $deleted = Widgetable::where('widgetable_id', $widgetableId)
            ->where('widgetable_type', 'App\Models\\' . $widgetableType)
            ->where('position', $position)
            ->delete();

        if (!$deleted) {
            return response()->json(['status' => 'error', 'message' => 'Widget could not be detached']);
        }

        // Update positions for all remaining widgets
        $widgetables = Widgetable::where('widgetable_id', $widgetableId)
            ->where('widgetable_type', 'App\Models\\' . $widgetableType)
            ->where('position', '>=', $position)
            ->orderBy('position')
            ->get();
        foreach ($widgetables as $widgetable) {
            $widgetable->decrement('position');
        }

        return response()->json(['status' => 'success', 'message' => 'Widget detached successfully']);
    }
}
