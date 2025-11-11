<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAiChatRequest;
use App\Http\Requests\UpdateAiChatRequest;
use App\Models\AiChat;
use App\Models\AiMessage;
use App\Models\AiPersona;
use App\Contracts\AiServiceInterface;
use App\Services\TokenCostCalculator;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiChatController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected AiServiceInterface $aiService, protected TokenCostCalculator $tokenCalculator)
    {
        $this->aiService = $aiService;
        $this->tokenCalculator = $tokenCalculator;
    }

    /**
     * Display a listing of the user's AI chats.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', AiChat::class);

        $chats = AiChat::with(['persona:id,name'])
            ->when($request->user(), function ($query) use ($request) {
                return $query->where('user_id', $request->user()->id);
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        // Add last message preview to each chat
        $chats->getCollection()->transform(function ($chat) {
            $lastMessage = $chat->messages()
                ->where('role', '!=', 'system')
                ->orderBy('created_at', 'desc')
                ->first();

            $chat->last_message_preview = $lastMessage
                ? substr($lastMessage->content, 0, 100)
                : 'New chat session...';

            return $chat;
        });

        return response()->json($chats);
    }

    /**
     * Store a newly created AI chat.
     */
    public function store(StoreAiChatRequest $request): JsonResponse
    {
        $this->authorize('create', AiChat::class);
        $persona = AiPersona::find($request->persona_id);
        $chat = AiChat::create([
            'session_name' => $request->session_name,
            'user_id' => $request->user()?->id,
            'ai_persona_id' => $request->persona_id,
            'ai_model_used' => $persona?->suggested_model ?? $request->ai_model_used ?? 'gpt-5-nano',
        ]);

        // If persona is selected, add system message
        if ($chat->ai_persona_id) {
            $persona = AiPersona::find($chat->ai_persona_id);
            if ($persona) {
                $chat->addMessage('system', $persona->system_prompt);
            }
        }

        return response()->json([
            'message' => 'Chat created successfully',
            'chat' => $chat->load('persona:id,name')
        ], 201);
    }

    /**
     * Display the specified AI chat with messages.
     */
    public function show(AiChat $aiChat): JsonResponse
    {
        $this->authorize('view', $aiChat);
        $aiChat->load([
            'messages' => function ($query) {
                $query->orderBy('created_at', 'asc');
            },
            'persona:id,name,description'
        ]);

        return response()->json($aiChat);
    }

    /**
     * Update the specified AI chat.
     */
    public function update(UpdateAiChatRequest $request, AiChat $aiChat): JsonResponse
    {
        $this->authorize('update', $aiChat);
        $aiChat->update($request->validated());

        return response()->json([
            'message' => 'Chat updated successfully',
            'chat' => $aiChat
        ]);
    }

    /**
     * Retrieve messages for a specific chat.
     */
    public function retrieveMessages(AiChat $chat): JsonResponse
    {
        $this->authorize('view', $chat);
        // Check if user owns this chat
        if ($chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $messages = $chat->messages()
            ->select('id', 'role', 'content', 'created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        $stats = [
            'total_messages' => $chat->messages()->count(),
            'total_tokens' => $chat->total_input_tokens + $chat->total_output_tokens,
            'total_cost' => $chat->total_cost_usd,
        ];

        return response()->json([
            'messages' => $messages,
            'stats' => $stats
        ]);
    }

    /**
     * Dispatch a message to the AI chat.
     */
    public function dispatchMessage(Request $request, AiChat $chat): JsonResponse
    {
        $this->authorize('update', $chat);
        $request->validate([
            'message' => 'required|string|max:10000',
            'persona_id' => 'nullable|exists:ai_personas,id'
        ]);

        // Check if user owns this chat
        if ($chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        try {
            // Update persona if provided
            if ($request->persona_id && $request->persona_id !== $chat->ai_persona_id) {
                $persona = AiPersona::find($request->persona_id);
                if ($persona && ($persona->is_public || $persona->created_by_user_id === auth()->id())) {
                    $chat->update(['ai_persona_id' => $request->persona_id, 'ai_model_used' => $persona->suggested_model]);
                }
            }

            // Add user message to chat
            $userMessage = $chat->addMessage('user', $request->message);

            // Build message history for API
            $messages = $chat->getMessageHistory();

            // Send to OpenAI API
            $payload = [
                'model' => $chat->ai_model_used,
                'messages' => $messages,
            ];
            $supportedModelsForTemperature = [
                'gpt-4',
                'gpt-4-turbo',
                'gpt-4-turbo-preview',
                'gpt-4-0125-preview',
                'gpt-4-1106-preview',
                'gpt-4-vision-preview',
                'gpt-4o',
                'gpt-4o-mini',
                'gpt-3.5-turbo',
                'gpt-3.5-turbo-16k',
                'gpt-3.5-turbo-0125',
                'gpt-3.5-turbo-1106',
            ];
            if (in_array($chat->ai_model_used, $supportedModelsForTemperature)) {
                $payload['max_tokens'] = 1000;
                $payload['temperature'] = 0.3;
            }
            $response = $this->aiService->chat($payload);

            // Extract token usage and response
            $inputTokens = $response['usage']['prompt_tokens'] ?? 0;
            $outputTokens = $response['usage']['completion_tokens'] ?? 0;
            $aiResponse = $response['choices'][0]['message']['content'] ?? '';

            // Calculate cost
            $cost = $this->tokenCalculator->calculateCost($chat->ai_model_used, $inputTokens, $outputTokens);

            // Add AI response to chat
            $aiMessage = $chat->addMessage('assistant', $aiResponse, $inputTokens, $outputTokens, $cost);

            return response()->json([
                'response' => $aiResponse,
                'tokens' => $inputTokens + $outputTokens,
                'cost' => number_format($cost, 4),
                'message_id' => $aiMessage->id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send message: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update persona for a chat.
     */
    public function updatePersona(Request $request, AiChat $chat): JsonResponse
    {
        $this->authorize('update', $chat);
        // Validate the request
        $request->validate([
            'persona_id' => 'nullable|exists:ai_personas,id'
        ]);

        // Check if user owns this chat
        if ($chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If persona_id is provided, verify user can access it
        if ($request->persona_id) {
            $persona = AiPersona::find($request->persona_id);
            if (!$persona || (!$persona->is_public && $persona->created_by_user_id !== auth()->id())) {
                return response()->json(['error' => 'Persona not found or access denied'], 404);
            }
        }

        // Update the chat
        $chat->update([
            'ai_persona_id' => $request->persona_id,
            'ai_model_used' => $persona->suggested_model
        ]);

        return response()->json([
            'message' => 'Chat persona updated successfully',
            'chat' => $chat->load('persona')
        ]);
    }

    /**
     * Remove the specified AI chat.
     */
    public function destroy(AiChat $aiChat): JsonResponse
    {
        $this->authorize('delete', $aiChat);
        $aiChat->delete();

        return response()->json([
            'message' => 'Chat deleted successfully'
        ]);
    }

    /**
     * Clear all messages from a chat (reset conversation).
     */
    public function clearMessages(AiChat $aiChat): JsonResponse
    {
        $this->authorize('delete', $aiChat);
        $aiChat->messages()->delete();

        // Reset token counts and costs
        // $aiChat->update([
        //     'total_input_tokens' => 0,
        //     'total_output_tokens' => 0,
        //     'total_cost_usd' => 0,
        // ]);

        // Re-add system message if persona exists
        // if ($aiChat->ai_persona_id) {
        //     $persona = AiPersona::find($aiChat->ai_persona_id);
        //     if ($persona) {
        //         $aiChat->addMessage('system', $persona->system_prompt);
        //     }
        // }

        return response()->json([
            'message' => 'Chat with messages deleted successfully'
        ]);
    }

    /**
     * Export chat messages.
     */
    public function export(AiChat $aiChat): JsonResponse
    {
        $this->authorize('view', $aiChat);
        $messages = $aiChat->messages()
            ->select('role', 'content', 'created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'chat_info' => [
                'session_name' => $aiChat->session_name,
                'model' => $aiChat->ai_model_used,
                'persona' => $aiChat->persona?->name,
                'total_cost' => $aiChat->total_cost_usd,
                'created_at' => $aiChat->created_at,
            ],
            'messages' => $messages
        ]);
    }

    /**
     * Purge all messages from a chat.
     */
    public function purgeChat(AiChat $chat): JsonResponse
    {
        $this->authorize('delete', $chat);

        $chat->messages()->delete();
        $chat->delete();

        // Reset token counts and costs
        // $chat->update([
        //     'total_input_tokens' => 0,
        //     'total_output_tokens' => 0,
        //     'total_cost_usd' => 0,
        // ]);

        // Re-add system message if persona exists
        // if ($chat->ai_persona_id) {
        //     $persona = AiPersona::find($chat->ai_persona_id);
        //     if ($persona) {
        //         $chat->addMessage('system', $persona->system_prompt);
        //     }
        // }

        return response()->json([
            'message' => 'Chat with messages deleted successfully.'
        ]);
    }

    /**
     * Download chat export.
     */
    public function downloadChat(AiChat $chat): JsonResponse
    {
        $this->authorize('view', $chat);
        $messages = $chat->messages()
            ->select('role', 'content', 'created_at')
            ->orderBy('created_at', 'asc')
            ->get();

        $exportData = [
            'chat_info' => [
                'session_name' => $chat->session_name,
                'model' => $chat->ai_model_used,
                'persona' => $chat->persona?->name,
                'total_cost' => $chat->total_cost_usd,
                'total_tokens' => $chat->total_input_tokens + $chat->total_output_tokens,
                'created_at' => $chat->created_at,
                'exported_at' => now(),
            ],
            'messages' => $messages,
            'stats' => [
                'total_messages' => $messages->count(),
                'user_messages' => $messages->where('role', 'user')->count(),
                'assistant_messages' => $messages->where('role', 'assistant')->count(),
                'system_messages' => $messages->where('role', 'system')->count(),
            ]
        ];

        return response()->json($exportData);
    }

    /**
     * Switch persona for a chat.
     */
    public function switchPersona(Request $request, AiChat $chat): JsonResponse
    {
        $request->validate([
            'persona_id' => 'nullable|exists:ai_personas,id'
        ]);

        // Check if user owns this chat
        if ($chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // If persona_id is provided, verify user can access it
        if ($request->persona_id) {
            $persona = AiPersona::find($request->persona_id);

            if (!$persona || (!$persona->is_public && $persona->created_by_user_id !== auth()->id())) {
                return response()->json(['error' => 'Persona not found or access denied'], 404);
            }
        }

        // Update the chat
        $chat->update([
            'ai_persona_id' => $request->persona_id
        ]);

        return response()->json([
            'message' => 'Chat persona updated successfully',
            'chat' => $chat->load('persona')
        ]);
    }
}
