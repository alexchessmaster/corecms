<?php

namespace App\Http\Controllers\Api;

use App\Models\Widget;
use App\Models\FieldValue;
use App\Models\PageWidget;
use App\Helpers\FileHelper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Resources\WidgetResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\PageWidgetResource;

use function Laravel\Prompts\error;

class PageWidgetController extends Controller
{
    public function fieldValue($pageId, $position, $lang = 'en')
    {
        if(!empty($lang)){
            app()->setLocale($lang);
        }
        $pageWidget = PageWidget::with('fieldValues.field')->where('page_id', $pageId)->where('position', $position)->first();

        return response()->json(new PageWidgetResource($pageWidget));
    }

    /**
     * example of input:
     *       // {
     *       //     "page-id":"1",
     *       //     "widget-position":"2",
     *       //     "language":"da",
     *       //     "field-id-2-field-value-id-3":"danish first field value two columns widget in page 1",
     *       //     "field-id-3-field-value-id-4":"danish second field value two columns widget in page 1"
     *       // }
     */
    public function updateFieldValue(Request $request)
    {
        // Log::info(json_encode($request->all()));
        $inputs = $request->all();

        $language = $inputs['language'];
        app()->setLocale($language);
        $widgetPosition = $inputs['widget-position'];
        $pageId = $inputs['page-id'];
        $widgetId = $inputs['widget-id'];
        $widgetLocked = $inputs['widget-locked'];
        unset($inputs['language']);
        unset($inputs['widget-position']);
        unset($inputs['page-id']);
        unset($inputs['widget-id']);
        unset($inputs['widget-locked']);

        foreach($inputs as $inputKey => $inputValue){

            $inputKeyArr = explode('-', $inputKey);
            $fieldValueId = null;
            foreach($inputKeyArr as $key => $value){
                $fieldId = $inputKeyArr[1];
                if(array_key_exists(3, $inputKeyArr)){
                    $fieldValueId = $inputKeyArr[3];
                }
            }

            
            if (Str::startsWith($inputValue, 'data:') && Str::contains($inputValue, ';base64,')) {
                // File widget
                // Split the base64 string into MIME type and file content
                $fileParts = explode(';base64,', $inputValue);
                $mimeType = str_replace('data:', '', $fileParts[0]); // Extract MIME type
                $base64Content = $fileParts[1]; // Extract base64 content

                // Decode the base64 content
                $decodedFile = base64_decode($base64Content, true);
            
                if ($decodedFile === false) {
                    return response()->json(['error' => 'Invalid base64 content'], 400);
                }
            
                // Generate a unique file name based on the MIME type
                $extension = Str::after($mimeType, '/'); // e.g., "jpeg"
                $fileName = uniqid() . '.' . $extension;
            
                // Define the file path
                $filePath = public_path('uploads/' . $fileName);
            
                // Ensure the uploads directory exists
                if (!file_exists(public_path('uploads'))) {
                    mkdir(public_path('uploads'), 0755, true);
                }
            
                // Save the decoded file to the uploads directory
                file_put_contents($filePath, $decodedFile);

                $inputValue = '/uploads/' . $fileName;
            }

            if(!empty($fieldValueId) && $fieldValueId !== 'undefined') {
                if(!empty($fieldId)) {
                    $fieldValueTmp = FieldValue::find($fieldValueId);
                    $fieldValueTmp->setTranslation('value', $language, $inputValue);
                    $fieldValueTmp->save();
                } else {
                    error('dsfkjdsjfjsdkf ' . json_encode(request()));
                }
            } else {
                $pageWidget = PageWidget::where('page_id', $pageId)->where('position', $widgetPosition)->first();
                if(!empty($pageWidget) && !empty($fieldId)){
                    $fieldValueTmp = new FieldValue;
                    $fieldValueTmp->field_id = $fieldId;
                    $fieldValueTmp->page_widget_id = $pageWidget->id;
                    $fieldValueTmp->setTranslation('value', $language, $inputValue);
                    $fieldValueTmp->save();
                } else {
                    error('dsfkjdsjdsfsdfsdfsfjsdkf ' . json_encode(request()));
                }
            }

            if($widgetLocked === '1') {
                // update FieldValue everywhere else
                $pageWidgetIds = PageWidget::where('widget_id', $widgetId)->pluck('id');
                $fieldValues = FieldValue::whereIn('page_widget_id', $pageWidgetIds)->where('field_id', $fieldId)->get();
                foreach($fieldValues as $fieldValueTmp){
                    $fieldValueTmp->setTranslation('value', $language, $inputValue);
                    $fieldValueTmp->save();
                }
            }
        }

        return response()->json([
            'message' => 'saved'
        ]);
    }
}
