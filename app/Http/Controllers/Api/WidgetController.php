<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Models\Widget;
use App\Models\PageWidget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetResource;
use App\Http\Resources\FieldWithValueResource;
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
                $tmpField->user_note = $matchingFieldValue->field->user_note;
                $tmpField->type = $matchingFieldValue->field->type;

                $field->vf->field = $tmpField;
            }

            return $field;
        });

        return response()->json(FieldWithValueResource::collection($allFieldsWithValues));
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
        $existingWidgets = $page->widgets()->orderBy('position', 'asc')->get();

        // If no widgets are attached, attach the widget with position 0
        if ($existingWidgets->isEmpty()) {
            $page->widgets()->attach($widgetId, ['position' => 0]);
        } else {
            $updatedWidgets = [];
            $positionUpdated = false;
            $i = 0;

            // Iterate through the existing widgets to reorganize positions
            foreach ($existingWidgets as $existingWidget) {
                // If the position matches where we want to add the new widget
                if (!$positionUpdated && $i == $addWidgetPosition) {
                    $updatedWidgets[] = ['id' => $widgetId, 'position' => $i];
                    $positionUpdated = true; // Mark that we've added the widget at the desired position
                }

                // Add the existing widget with its current position
                $updatedWidgets[] = ['id' => $existingWidget->id, 'position' => $i];
                $i++;
            }

            // If the new widget wasn't added, it goes to the end
            if (!$positionUpdated) {
                $updatedWidgets[] = ['id' => $widgetId, 'position' => $i];
            }

            // Now, sync the widgets with the updated positions
            $page->widgets()->sync([]);
            foreach ($updatedWidgets as $widgetData) {
                $page->widgets()->attach($widgetData['id'], ['position' => $widgetData['position']]);
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
        $widgets = $page->widgets()->orderBy('position', 'asc')->get();

        // Update positions for all remaining widgets
        foreach ($widgets as $index => $widget) {
            $page->widgets()->updateExistingPivot($widget->id, ['position' => $index]);
        }

        return response()->json(['status' => 'success', 'message' => 'Widget detached successfully', 'pageWidgets' => $page->widgets]);
    }
}
