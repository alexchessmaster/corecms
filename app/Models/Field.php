<?php

namespace App\Models;

use App\Models\Widget;
use App\Models\FieldWidget;
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
