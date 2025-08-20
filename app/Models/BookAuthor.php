<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookAuthor extends Model
{
    /** @use HasFactory<\Database\Factories\BookAuthorFactory> */
    use HasFactory;
    use HasTranslations;

    public $translatable = ['name', 'nationality', 'biography'];
    protected $fillable = ['name', 'date_of_birth', 'date_of_death', 'image', 'biography', 'nationality'];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'date_of_death' => 'datetime',
    ];

    public function books()
    {
        return $this->hasMany(Book::class, 'author_id', 'id');
    }
}
