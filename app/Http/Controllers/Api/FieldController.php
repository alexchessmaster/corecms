<?php

namespace App\Http\Controllers\Api;

use App\Models\Field;
use App\Models\Widget;
use App\Models\PageWidget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FieldResource;

class FieldController extends Controller
{
    public function store(Request $request)
    {
        $widgetId = $request->widget_id;
        $userNote = $request->user_note;
        $type = $request->type;

        $field = new Field;
        $field->widget_id = $widgetId;
        $field->user_note = $userNote;
        $field->type = $type;
        // save default value for each language?
        $field->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Field created',
            'field' => new FieldResource($field)
        ], 200);
    }

    public function update(Request $request, Field $field)
    {
        
    }

    public function destroy(Request $request, Field $field)
    {
        $field->delete();
    }

}
