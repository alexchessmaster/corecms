<?php

namespace App\Modules\Products\Models;

use App\Models\User;
use App\Modules\Products\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductAuthor extends Model
{
    /** @use HasFactory<\Database\Factories\ProductAuthorFactory> */
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name', 'nationality', 'biography'];
    protected $fillable = ['name', 'date_of_birth', 'date_of_death', 'image', 'biography', 'nationality'];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'date_of_death' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'author_id', 'id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if($user->can('view product authors')) {
            return $query;
        }

        if($user->can('view own product authors')) {
            return $query->where('user_id', $user->id);
        }

        return $query->whereRaw('1 = 0');
    }
}
