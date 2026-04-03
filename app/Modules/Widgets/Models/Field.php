<?php

namespace App\Modules\Widgets\Models;

use App\Modules\Widgets\Models\Widget;
use App\Modules\Widgets\Models\FieldWidget;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Field extends Model
{
    use HasTranslations;

    protected $translatable = ['value'];
    protected $guarded = [];

    public function fieldWidgets()
    {
        return $this->hasMany(FieldWidget::class);
    }
}
