<?php

namespace App\Modules\AiChat\Contracts;

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
