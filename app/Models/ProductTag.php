<?php

namespace App\Models;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTag extends Model
{
    /** @use HasFactory<\Database\Factories\ProductTagFactory> */
    use HasFactory;
    use HasTranslations;

    protected $fillable = ['name', 'slug'];
    public $translatable = ['name', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    public function scopeVisibleTo($query, User $user)
    {
        if($user->can('view product tags')) {
            return $query;
        }

        if($user->can('view own product tags')) {
            return $query->where('user_id', $user->id);
        }
    }
}
