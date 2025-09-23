<?php

namespace App\Models;

use App\Models\User;
use App\Models\AiChat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiPersona extends Model
{
    protected $fillable = [
        'name',
        'description',
        'system_prompt',
        'suggested_model',
        'default_parameters',
        'created_by_user_id',
        'is_public',
        'is_active',
    ];

    protected $casts = [
        'default_parameters' => 'array',
        'is_public' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function chats(): HasMany
    {
        return $this->hasMany(AiChat::class, 'ai_persona_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
