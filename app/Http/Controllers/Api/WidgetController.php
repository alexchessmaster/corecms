<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetResource;
use App\Models\Page;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetController extends Controller
{
    public function show($id)
    {
        $widget = Widget::with('fields')->find($id);

        return response()->json(new WidgetResource($widget));
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

        // Get the widget at the given position
        $widget = $page->widgets()->wherePivot('position', $positionId)->first();
        if (! $widget) {
            return response()->json(['status' => 'error', 'message' => 'No widget found at the given position']);
        }

        // Detach the widget from the page
        $page->widgets()->detach($widget->id);

        // After detaching, reorganize the positions of the remaining widgets
        $widgets = $page->widgets()->orderBy('position', 'asc')->get();

        // Update positions for all remaining widgets
        foreach ($widgets as $index => $widget) {
            $page->widgets()->updateExistingPivot($widget->id, ['position' => $index]);
        }

        return response()->json(['status' => 'success', 'message' => 'Widget detached successfully', 'pageWidgets' => $page->widgets]);
    }
}
