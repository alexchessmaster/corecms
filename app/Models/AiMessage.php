<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'ai_chat_id',
        'role',
        'content',
        'input_tokens',
        'output_tokens',
        'message_cost_usd',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'message_cost_usd' => 'decimal:6',
    ];

    public function aiChat(): BelongsTo
    {
        return $this->belongsTo(AiChat::class);
    }
}
