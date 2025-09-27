<?php

namespace App\Services;

class TokenCostCalculator
{
    // Pricing per 1K tokens (as of 2025)
    private const MODEL_PRICING = [
        'gpt-5' => [
            'input' => 0.00125, // $1.25 / 1M
            'output' => 0.010,  // $10 / 1M
        ],
        'gpt-5-mini' => [
            'input' => 0.00025, // $0.25 / 1M
            'output' => 0.0020, // $2 / 1M
        ],
        'gpt-5-nano' => [
            'input' => 0.00005, // $0.05 / 1M
            'output' => 0.0004, // $0.40 / 1M
        ],
        'gpt-4.1' => [
            'input' => 0.0030,  // $3 / 1M
            'output' => 0.012,  // $12 / 1M
        ],
        'gpt-4.1-mini' => [
            'input' => 0.0008,  // $0.80 / 1M
            'output' => 0.0032, // $3.20 / 1M
        ],
        'gpt-4.1-nano' => [
            'input' => 0.0002,  // $0.20 / 1M
            'output' => 0.0008, // $0.80 / 1M
        ],
        'gpt-4o-mini' => [
            'input' => 0.0006,  // $0.60 / 1M
            'output' => 0.0024, // $2.40 / 1M
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
