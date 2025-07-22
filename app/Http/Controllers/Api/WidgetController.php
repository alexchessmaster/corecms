<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Widget;
use App\Models\PageWidget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetResource;
use App\Http\Resources\FieldWithValueResource;
use App\Http\Resources\PageWidgetResource;
use App\Models\FieldValue;
use App\Models\Widgetable;
use stdClass;

class WidgetController extends Controller
{
    public function show($id)
    {
        $widget = Widget::with('fieldWidgets.field')->find($id);

        return response()->json(new WidgetResource($widget));
    }

    // In your controller
    public function getWidgetFieldsWithValues($pageId, $widgetId, $position, $lang = 'en')
    {
        if (!empty($lang)) {
            app()->setLocale($lang);
        }

        $widget = Widget::with('fields')->find($widgetId);
        
        if (!$widget) {
            return response()->json(['error' => 'Widget not found'], 404);
        }
        
        // $pageWidget = PageWidget::with('fieldValues.field')
        //     ->where('page_id', $pageId)
        //     ->where('position', $position)
        //     ->first();

        $widgetable = Widgetable::where('content_id', $pageId)
            ->where('content_type', 'App\Models\Page')
            ->where('position', $position)
            ->first();

        // dd($pageWidget);

        if (!$widgetable) {
            return response()->json(['error' => 'Page widget not found'], 404);
        }

        $fields = $widget->fields;
        $fieldValues = $widgetable->fieldValues;

        $allFieldsWithValues = $fields->map(function ($field) use ($fieldValues) {
            $matchingFieldValue = $fieldValues->firstWhere('field_id', $field->id);
            if ($matchingFieldValue) {
                $field->vf = new \stdClass;
                $field->vf->id = $matchingFieldValue->id;
                $field->vf->value = $matchingFieldValue->getTranslation('value', app()->getLocale());
                $field->vf->page_widget_id = $matchingFieldValue->page_widget_id;
                $field->vf->field_id = $matchingFieldValue->field_id;

                $tmpField = new \stdClass;
                $tmpField->id = $matchingFieldValue->field->id;
                $tmpField->page_widget_id = $matchingFieldValue->field->page_widget_id;
                $tmpField->key = $matchingFieldValue->field->key;
                $tmpField->type = $matchingFieldValue->field->type;

                $field->vf->field = $tmpField;
            }

            return $field;
        });

        return response()->json([
            'widget' => new WidgetResource($widget),
            'field_with_value' => FieldWithValueResource::collection($allFieldsWithValues)
        ]);
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

        $widgetables = Widgetable::where('widgetable_id', $widgetableId)
            ->where('widgetable_type', 'App\Models\\' . $widgetableType)
            ->where('position', '>=', $addWidgetPosition)
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


        if ($widget->locked_fields_value) {
            // TODO: fix this later
            // // if locked_fields_value is true, we need some null values for each field
            // $fields = $widget->fields;
            // $pageWidgetLast = PageWidget::where('widget_id', $widget->id)->orderBy('id', 'desc')->first();
            // foreach($fields as $field) {
            //     $fieldValueTmp = new FieldValue;
            //     $fieldValueTmp->field_id = $field->id;
            //     $fieldValueTmp->page_widget_id = $pageWidgetLast->id;
            //     $fieldValueTmp->value = null;
            //     $fieldValueTmp->save();
            // }

            // // initialize with the same values in another place when we add a new widget
            // // if there is another value for this widget, we should copy the old data and create new filed_values
            // $pageWidget = PageWidget::where('widget_id', $widget->id)->first();
            // $fieldValues = FieldValue::where('page_widget_id', $pageWidget->id)->get();
            // foreach($fieldValues as $fieldValue) {
            //     $fieldValueTmp = FieldValue::where('field_id', $fieldValue->field_id)->where('page_widget_id', $pageWidgetLast->id)->first();
            //     $fieldValueTmp->value = $fieldValue->value;
            //     $fieldValueTmp->save();
            // }
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
