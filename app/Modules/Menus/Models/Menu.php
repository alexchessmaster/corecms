<?php

namespace App\Modules\Menus\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Menu extends Model
{
    use HasTranslations;

    protected $translatable = ['name', 'link', 'description'];
    protected $fillable = ['user_id', 'name', 'link', 'parent_id', 'order', 'image', 'image_alt', 'description'];
    protected $with = ['parent'];

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if($user->can('view menus')) {
            return $query;
        }

        if($user->can('view own menus')) {
            return $query->where('user_id', $user->id);
        }
    }
}
