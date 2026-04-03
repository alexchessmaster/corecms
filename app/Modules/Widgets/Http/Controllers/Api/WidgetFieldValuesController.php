<?php

namespace App\Modules\Widgets\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Widgets\Http\Requests\StoreWidgetFieldValuesRequest;
use App\Modules\Widgets\Http\Requests\UpdateWidgetFieldValuesRequest;
use App\Modules\Widgets\Models\Field;
use App\Modules\Widgets\Models\FieldWidget;
use App\Modules\Widgets\Models\Widgetable;
use App\Modules\Widgets\Models\WidgetFieldValues;
use App\Modules\Shared\Helpers\StrHelper;
use App\Modules\Shared\Jobs\ProcessImageJob;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use function Pest\Laravel\json;

class WidgetFieldValuesController extends Controller
{
    use AuthorizesRequests;

    private $authorized = false;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(WidgetFieldValues $widgetFieldValues)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $inputs = $request->all();

        $language = $inputs['language'];
        app()->setLocale($language);
        $widgetPosition = $inputs['widget-position'];
        $widgetableId = $inputs['widgetable-id'];  // page ID or other widgetable ID
        $widgetableType = $inputs['widgetable-type'];
        $widgetId = $inputs['widget-id'];
        $widgetLocked = $inputs['widget-locked'];
        unset($inputs['language']);
        unset($inputs['widget-position']);
        unset($inputs['widgetable-id']);
        unset($inputs['widgetable-type']);
        unset($inputs['widget-id']);
        unset($inputs['widget-locked']);

        $fieldWidget = null;

        foreach ($inputs as $inputKey => $inputValue) {
            $inputKeyArr = explode('-', $inputKey);

            $widgetable_id = $inputKeyArr[1] ?? null; // ID in the widgetables table
            $field_widget_id = $inputKeyArr[3] ?? null;
            $widget_field_value_id = $inputKeyArr[5] ?? null;

            $widgetable = Widgetable::find($widgetable_id);

            $this->isAuthorized($widgetable);

            $fieldWidget = FieldWidget::find($field_widget_id);
            $widgetFieldValue = WidgetFieldValues::where('widgetable_id', $widgetable->id)
                ->where('field_widget_id', $fieldWidget->id)
                ->first();

            $field = $fieldWidget->field;
            if ($field->type === 'textarea_one_line') {
                // strip tags from textarea_one_line
                $inputValue = substr($inputValue, 3, strlen($inputValue) - 7);
            }

            if (!empty($widgetFieldValue) && $widget_field_value_id && $widget_field_value_id !== 'null' && $widget_field_value_id !== 'undefined') {

                if (!$widgetable || !$fieldWidget) {
                    info('Widgetable or FieldWidget not found');
                    continue;
                }


                // Check if the input is a file and no new value is provided
                if (Str::startsWith($widgetFieldValue->value, '/uploads/') && empty($inputValue)) {
                    $inputValue = $widgetFieldValue->getTranslation('value', $language, false); // Retain old value
                } elseif (Str::startsWith($inputValue, 'data:') && Str::contains($inputValue, ';base64,')) {
                    // Handle new file input
                    $inputValue = $this->handleBase64File($inputValue);
                } else {
                    // Plain text
                    $inputValue = StrHelper::removeUnicodeCharacters($inputValue);
                }

                $widgetFieldValue->setTranslation('value', $language, $inputValue);
                $widgetFieldValue->save();
            } else {
                // If widgetFieldValueId is not provided, create a new entry
                if (Str::startsWith($inputValue, 'data:') && Str::contains($inputValue, ';base64,')) {
                    $inputValue = $this->handleBase64File($inputValue);
                }

                $widgetFieldValue =  new WidgetFieldValues;
                $widgetFieldValue->widgetable_id = $widgetable->id;
                $widgetFieldValue->field_widget_id = $fieldWidget->id;
                $widgetFieldValue->setTranslation('value', $language, $inputValue);
                $widgetFieldValue->save();
            }

            // TODO: This part should be tested later
            if ($widgetLocked === '1') {
                // Find all widgetables using the same widget_id across all pages/articles/categories
                $widgetables = Widgetable::where('widget_id', $fieldWidget->widget_id)->get();

                foreach ($widgetables as $otherWidgetable) {
                    // Skip the current widgetable to avoid overwriting its value
                    if ($otherWidgetable->id == $widgetable->id) {
                        continue;
                    }

                    $fieldWidgetForOther = FieldWidget::where('widget_id', $fieldWidget->widget_id)
                        ->where('field_id', $fieldWidget->field_id)
                        ->first();

                    if (!$fieldWidgetForOther) {
                        continue;
                    }

                    $widgetFieldValueForOther = WidgetFieldValues::where('widgetable_id', $otherWidgetable->id)
                        ->where('field_widget_id', $fieldWidgetForOther->id)
                        ->first();

                    if ($widgetFieldValueForOther) {
                        // Only update if the existing value is empty or null
                        $existingValue = $widgetFieldValueForOther->getTranslation('value', $language);
                        if (empty($existingValue)) {
                            $widgetFieldValueForOther->setTranslation('value', $language, $inputValue);
                            $widgetFieldValueForOther->save();
                        }
                    } else {
                        // Create if not exists
                        $newFieldValue = new WidgetFieldValues;
                        $newFieldValue->widgetable_id = $otherWidgetable->id;
                        $newFieldValue->field_widget_id = $fieldWidgetForOther->id;
                        $newFieldValue->setTranslation('value', $language, $inputValue);
                        $newFieldValue->save();
                    }
                }
            }
        }

        return response()->json([
            'message' => isset($fieldWidget) ? $fieldWidget?->key . ' saved successfully.' : 'Form saved successfully.'
        ]);
    }

    private function handleBase64File($base64Data)
    {
        $fileParts = explode(';base64,', $base64Data, 2);

        if (count($fileParts) !== 2) {
            throw new \Exception('Invalid base64 format');
        }

        $mimeType = str_replace('data:', '', $fileParts[0]);
        $base64Content = $fileParts[1];

        $extension = Str::after($mimeType, '/');
        $extension = Str::before($extension, '+');

        $fileName = Str::uuid() . '.' . $extension;
        $uploadDir = public_path('uploads');
        $filePath = $uploadDir . '/' . $fileName;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Open output file
        $output = fopen($filePath, 'w');

        // Create input stream
        $input = fopen('php://temp', 'r+');
        fwrite($input, $base64Content);
        rewind($input);

        // Attach base64 decoding filter
        stream_filter_append($input, 'convert.base64-decode');

        // Stream copy
        stream_copy_to_stream($input, $output);

        fclose($input);
        fclose($output);

        $fullPath = $uploadDir . '/' . $fileName;
        ProcessImageJob::dispatch($fullPath, false);

        return '/uploads/' . $fileName;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WidgetFieldValues $widgetFieldValues)
    {
        //
    }

    private function isAuthorized($widgetable)
    {
        if ($this->authorized) {
            return true;
        }

        $modelClass = $widgetable->widgetable_type;
        $widgetableModel = $modelClass::find($widgetable->widgetable_id);
        $this->authorize('update', $widgetableModel);
        $this->authorized = true;
        return true;
    }
}
