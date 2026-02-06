<?php

namespace App\Modules\AiChats\Models;

use App\Modules\AiChats\Models\AiMessage;
use App\Modules\AiChats\Models\AiPersona;
use App\Services\TokenCostCalculator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiChat extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'session_name',
        'user_id',
        'ai_model_used',
        'ai_persona_id',
        'total_input_tokens',
        'total_output_tokens',
        'total_cost_usd',
        'pricing_config',
    ];

    protected $casts = [
        'pricing_config' => 'array',
        'total_cost_usd' => 'decimal:6',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(AiMessage::class, 'ai_chat_id');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(AiPersona::class, 'ai_persona_id');
    }

    public function addMessage(string $role, string $content, ?int $inputTokens = null, ?int $outputTokens = null): AiMessage
    {
        $calculator = new TokenCostCalculator();
        $messageCost = 0;

        if ($inputTokens || $outputTokens) {
            $messageCost = $calculator->calculateCost(
                $this->ai_model_used,
                $inputTokens ?? 0,
                $outputTokens ?? 0
            );

            $this->increment('total_input_tokens', $inputTokens ?? 0);
            $this->increment('total_output_tokens', $outputTokens ?? 0);
            $this->increment('total_cost_usd', $messageCost);
        }

        return $this->messages()->create([
            'role' => $role,
            'content' => $content,
            'input_tokens' => $inputTokens,
            'output_tokens' => $outputTokens,
            'message_cost_usd' => $messageCost,
        ]);
    }

    public function getMessageHistory(): array
    {
        return $this->messages()
            ->orderBy('created_at')
            ->get()
            ->map(function ($message) {
                return [
                    'role' => $message->role,
                    'content' => $message->content,
                ];
            })
            ->toArray();
    }
}
