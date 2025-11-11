<?php

namespace App\Services;

use App\Contracts\AiServiceInterface;
use Illuminate\Support\Facades\Http;

class OpenAiService implements AiServiceInterface
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openai.com/v1';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    public function chat(array $payload): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->baseUrl . '/chat/completions', $payload);

        if ($response->failed()) {
            throw new \Exception('OpenAI API request failed: ' . $response->body());
        }

        return $response->json();
    }
}
