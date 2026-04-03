<?php

namespace App\Modules\Widgets\Models;

use App\Modules\Widgets\Models\Widget;
use App\Models\FieldValue;
use App\Modules\Widgets\Models\WidgetFieldValues;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Widgetable extends Model
{
    /** @use HasFactory<\Database\Factories\WidgetableFactory> */
    use HasFactory;

    public $timestamps = false;
    protected $fillable = ["id", "widgetable_type", "widgetable_id", "widget_id", "position"];

    public function widget()
    {
        return $this->belongsTo(Widget::class);
    }

    public function widgetable()
    {
        return $this->morphTo();
    }

    public function widgetFieldValues()
    {
        return $this->hasMany(WidgetFieldValues::class, 'widgetable_id');
    }
}
