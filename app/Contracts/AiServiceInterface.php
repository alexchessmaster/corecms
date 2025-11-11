<?php

namespace App\Contracts;

interface AiServiceInterface
{
    /**
     * Send a chat request to the AI service.
     *
     * @param array $payload
     * @return array
     * @throws \Exception
     */
    public function chat(array $payload): array;
}
