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
use stdClass;

class WidgetController extends Controller
{
    public function show($id)
    {
        $widget = Widget::with('fields')->find($id);

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
        
        $pageWidget = PageWidget::with('fieldValues.field')
            ->where('page_id', $pageId)
            ->where('position', $position)
            ->first();

        if (!$pageWidget) {
            return response()->json(['error' => 'Page widget not found'], 404);
        }

        $fields = $widget->fields;
        $fieldValues = $pageWidget->fieldValues;

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
        $pageId = request()->input('pageId');
        $addWidgetPosition = request()->input('addWidgetPosition');

        // Find the widget and page
        $widget = Widget::find($widgetId);
        if (! $widget) {
            return response()->json(['status' => 'error', 'message' => 'Widget not found', 'request' => request()->all()]);
        }

        $page = Page::find($pageId);
        if (! $page) {
            return response()->json(['status' => 'error', 'message' => 'Page not found', 'request' => request()->all()]);
        }

        // Get the current widgets attached to the page
        $pageWidgets = PageWidget::where('page_id', $page->id)->where('position', '>=', $addWidgetPosition)->get();
        foreach($pageWidgets as $pageWidget) {
            $pageWidget->increment('position');
        }
        $page->widgets()->attach($widgetId, ['position' => $addWidgetPosition]);

        if($widget->locked_fields_value) {
            // if locked_fields_value is true, we need some null values for each field
            $fields = $widget->fields;
            $pageWidgetLast = PageWidget::where('widget_id', $widget->id)->orderBy('id', 'desc')->first();
            foreach($fields as $field) {
                $fieldValueTmp = new FieldValue;
                $fieldValueTmp->field_id = $field->id;
                $fieldValueTmp->page_widget_id = $pageWidgetLast->id;
                $fieldValueTmp->value = null;
                $fieldValueTmp->save();
            }
            
            // initialize with the same values in another place when we add a new widget
            // if there is another value for this widget, we should copy the old data and create new filed_values
            $pageWidget = PageWidget::where('widget_id', $widget->id)->first();
            $fieldValues = FieldValue::where('page_widget_id', $pageWidget->id)->get();
            foreach($fieldValues as $fieldValue) {
                $fieldValueTmp = FieldValue::where('field_id', $fieldValue->field_id)->where('page_widget_id', $pageWidgetLast->id)->first();
                $fieldValueTmp->value = $fieldValue->value;
                $fieldValueTmp->save();
            }
        }
        
        return response()->json(['status' => 'success', 'pageWidgets' => $page->widgets]);
    }

    public function detach()
    {
        $pageId = request()->pageId;
        $positionId = request()->positionId;

        // Find the page
        $page = Page::find($pageId);
        if (! $page) {
            return response()->json(['status' => 'error', 'message' => 'Page not found']);
        }

        // Detach the widget from the page
        $widget = $page->widgets()->wherePivot('position', $positionId)->detach();

        // After detaching, reorganize the positions of the remaining widgets
        $pageWidgets = $page->pageWidgets()->orderBy('position', 'asc')->get();

        // Update positions for all remaining widgets
        foreach ($pageWidgets as $index => $pageWidget) {
            $pageWidget->position = $index;
            $pageWidget->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Widget detached successfully', 'pageWidgets' => $page->widgets]);
    }
}
