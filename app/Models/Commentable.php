<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Commentable extends Model
{
    /** @use HasFactory<\Database\Factories\CommentableFactory> */
    use HasFactory;
    use HasTranslations;

    public $translatable = ['content'];

    protected $fillable = [
        'commentable_id',
        'commentable_type',
        'content',
        'user_id',
        'name',
        'email',
        'stars',
        'status',
    ];
}
