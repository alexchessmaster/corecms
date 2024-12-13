<?php

namespace App\Http\Controllers\Api;

use App\Models\Widget;
use App\Models\PageWidget;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PageWidgetResource;
use App\Http\Resources\WidgetResource;
use App\Models\FieldValue;
use Illuminate\Support\Facades\Log;

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

    public function updateFieldValue(Request $request)
    {
        // Log::info(json_encode($request->all()));
        $inputs = $request->all();
        $language = $inputs['language'];
        app()->setLocale($language);
        $widgetPosition = $inputs['widget-position'];
        $pageId = $inputs['page-id'];
        unset($inputs['language']);
        unset($inputs['widget-position']);
        unset($inputs['page-id']);
        foreach($inputs as $inputKey => $inputValue){

            $inputKeyArr = explode('-', $inputKey);
            $fieldValueId = null;
            foreach($inputKeyArr as $key => $value){
                // info('hiiiiiiiii-1');
                $fieldId = $inputKeyArr[1];
                // info('hiiiiiiiii-2');
                if(array_key_exists(3, $inputKeyArr)){
                    $fieldValueId = $inputKeyArr[3];
                }
            }

            if(!empty($fieldValueId) && $fieldValueId !== 'undefined') {
                // Log::info('nnnnnnnnnnnnnnot empty' . $fieldValueId);
                if(!empty($fieldId)) {
                    $fieldValueTmp = FieldValue::find($fieldValueId);
                    $fieldValueTmp->setTranslation('value', $language, $inputValue);
                    $fieldValueTmp->save();
                }
                // $fieldValueTmp

            } else {
                $pageWidget = PageWidget::where('page_id', $pageId)->where('position', $widgetPosition)->first();
                if(!empty($pageWidget) && !empty($fieldId)){
                    info('uuu');
                    $fieldValueTmp = new FieldValue;
                    $fieldValueTmp->field_id = $fieldId;
                    $fieldValueTmp->page_widget_id = $pageWidget->id;
                    $fieldValueTmp->setTranslation('value', $language, $inputValue);
                    $fieldValueTmp->save();
                }
                info('uuu2');
            }

            
            // strpos('expl')
            // {
            //     "page-id":"1",
            //     "widget-position":"2",
            //     "language":"da",
            //     "field-id-2-field-value-id-3":"danish first field value two columns widget in page 1",
            //     "field-id-3-field-value-id-4":"danish second field value two columns widget in page 1"
            // }

        }

        return response()->json([
            'message' => ''
        ]);
    }
}
