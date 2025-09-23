<?php

namespace App\Services;

class TokenCostCalculator
{
    // Pricing per 1K tokens (as of 2025)
    private const MODEL_PRICING = [
        'gpt-5' => [
            'input' => 0.0025,
            'output' => 0.010,
        ],
        'gpt-4o' => [
            'input' => 0.0025,
            'output' => 0.010,
        ],
        'gpt-4-turbo' => [
            'input' => 0.010,
            'output' => 0.030,
        ],
        'gpt-3.5-turbo' => [
            'input' => 0.0005,
            'output' => 0.0015,
        ],
    ];

    public function calculateCost(string $modelName, int $inputTokens, int $outputTokens): float
    {
        $pricing = self::MODEL_PRICING[$modelName] ?? self::MODEL_PRICING['gpt-3.5-turbo'];
        
        $inputCost = ($inputTokens / 1000) * $pricing['input'];
        $outputCost = ($outputTokens / 1000) * $pricing['output'];
        
        return round($inputCost + $outputCost, 6);
    }

    public function getModelPricing(string $modelName): array
    {
        return self::MODEL_PRICING[$modelName] ?? self::MODEL_PRICING['gpt-3.5-turbo'];
    }

    public function estimateCostForText(string $text, string $modelName = 'gpt-3.5-turbo'): float
    {
        // Rough estimation: 1 token ≈ 4 characters for English text
        $estimatedTokens = ceil(strlen($text) / 4);
        return $this->calculateCost($modelName, $estimatedTokens, 0);
    }
}