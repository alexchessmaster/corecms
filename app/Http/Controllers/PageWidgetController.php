<?php

namespace App\Http\Controllers;

// use App\Models\Page;
// use App\Models\Field;
// use App\Models\Widget;
// use App\Models\FieldValue;
// use App\Models\PageWidget;
// use Illuminate\Support\Str;
// use Illuminate\Http\Request;
// use function Laravel\Prompts\error;
// use Illuminate\Support\Facades\Log;
// use App\Http\Controllers\Controller;
// use App\Http\Resources\WidgetResource;

// use Illuminate\Support\Facades\Storage;
// use App\Http\Resources\PageWidgetResource;

class PageWidgetController extends Controller
{
    // this file should be deleted use WidgetableController instead
    // public function fieldValue($pageId, $position, $lang = 'en')
    // {
    //     if (!empty($lang)) {
    //         app()->setLocale($lang);
    //     }
    //     $pageWidget = PageWidget::with('fieldValues.field')->where('page_id', $pageId)->where('position', $position)->first();

    //     return response()->json(new PageWidgetResource($pageWidget));
    // }

    // /**
    //  * example of input:
    //  *       // {
    //  *       //     "page-id":"1",
    //  *       //     "widget-position":"2",
    //  *       //     "language":"da",
    //  *       //     "field-id-2-field-value-id-3":"danish first field value two columns widget in page 1",
    //  *       //     "field-id-3-field-value-id-4":"danish second field value two columns widget in page 1"
    //  *       // }
    //  */
    // public function updateFieldValue(Request $request)
    // {
    //     Log::info(json_encode('$request->all()'));
    //     $inputs = $request->all();

    //     $language = $inputs['language'];
    //     app()->setLocale($language);
    //     $widgetPosition = $inputs['widget-position'];
    //     $pageId = $inputs['page-id'];
    //     $widgetId = $inputs['widget-id'];
    //     $widgetLocked = $inputs['widget-locked'];
    //     unset($inputs['language']);
    //     unset($inputs['widget-position']);
    //     unset($inputs['page-id']);
    //     unset($inputs['widget-id']);
    //     unset($inputs['widget-locked']);

    //     foreach ($inputs as $inputKey => $inputValue) {
    //         $inputKeyArr = explode('-', $inputKey);
    //         $fieldValueId = $inputKeyArr[3] ?? null;
    //         $fieldId = $inputKeyArr[1] ?? null;

    //         // strip tags from textarea_one_line
    //         $field = Field::find($fieldId);
    //         if($field->type === 'textarea_one_line'){
    //             $inputValue = substr($inputValue, 3, strlen($inputValue) - 7);
    //         }

    //         if ($fieldValueId && $fieldValueId !== 'undefined') {
    //             $fieldValueTmp = FieldValue::find($fieldValueId);
    //             if (!$fieldValueTmp) {
    //                 continue;
    //             }

    //             // Check if the input is a file and no new value is provided
    //             if (Str::startsWith($fieldValueTmp->value, '/uploads/') && empty($inputValue)) {
    //                 $inputValue = $fieldValueTmp->getTranslation('value', $language); // Retain old value
    //             } elseif (Str::startsWith($inputValue, 'data:') && Str::contains($inputValue, ';base64,')) {
    //                 // Handle new file input
    //                 $inputValue = $this->handleBase64File($inputValue);
    //             }

    //             $fieldValueTmp->setTranslation('value', $language, $inputValue);
    //             $fieldValueTmp->save();
    //         } else {
    //             // Handle new entries or missing fieldValueId
    //             $pageWidget = PageWidget::where('page_id', $pageId)->where('position', $widgetPosition)->first();
    //             if ($pageWidget && $fieldId) {
    //                 $fieldValueTmp = new FieldValue;
    //                 $fieldValueTmp->field_id = $fieldId;
    //                 $fieldValueTmp->page_widget_id = $pageWidget->id;

    //                 if (Str::startsWith($inputValue, 'data:') && Str::contains($inputValue, ';base64,')) {
    //                     $inputValue = $this->handleBase64File($inputValue);
    //                 }

    //                 $fieldValueTmp->setTranslation('value', $language, $inputValue);
    //                 $fieldValueTmp->save();
    //             }
    //         }

    //         if ($widgetLocked === '1') {
    //             // Update FieldValue everywhere else
    //             $pageWidgetIds = PageWidget::where('widget_id', $widgetId)->pluck('id');
    //             $fieldValues = FieldValue::whereIn('page_widget_id', $pageWidgetIds)->where('field_id', $fieldId)->get();
    //             foreach ($fieldValues as $fieldValueTmp) {
    //                 $fieldValueTmp->setTranslation('value', $language, $inputValue);
    //                 $fieldValueTmp->save();
    //             }
    //         }
    //     }

    //     return response()->json([
    //         'message' => 'saved'
    //     ]);
    // }

    // private function handleBase64File($base64Data)
    // {
    //     $fileParts = explode(';base64,', $base64Data);
    //     $mimeType = str_replace('data:', '', $fileParts[0]); // Extract MIME type
    //     $base64Content = $fileParts[1]; // Extract base64 content

    //     // Decode the base64 content
    //     $decodedFile = base64_decode($base64Content, true);
    //     if ($decodedFile === false) {
    //         throw new \Exception('Invalid base64 content');
    //     }

    //     // Generate a unique file name
    //     $extension = Str::after($mimeType, '/');
    //     $extension = Str::before($extension, '+'); // Remove anything after "+"
    //     $fileName = uniqid() . '.' . $extension;

    //     // Define the file path
    //     $filePath = public_path('uploads/' . $fileName);

    //     // Ensure the uploads directory exists
    //     if (!file_exists(public_path('uploads'))) {
    //         mkdir(public_path('uploads'), 0755, true);
    //     }

    //     // Save the file
    //     file_put_contents($filePath, $decodedFile);

    //     return '/uploads/' . $fileName;
    // }
}
