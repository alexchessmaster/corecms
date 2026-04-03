<?php

namespace App\Modules\AiChat\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AiChat\Http\Requests\StoreAiChatRequest;
use App\Modules\AiChat\Http\Requests\UpdateAiChatRequest;
use App\Modules\AiChat\Models\AiChat;
use App\Modules\AiChat\Models\AiMessage;
use App\Modules\AiChat\Models\AiPersona;
use App\Modules\AiChat\Contracts\AiServiceInterface;
use App\Modules\Shared\Actions\TokenCostCalculatorAction;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiChatController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private AiServiceInterface $aiService, private TokenCostCalculatorAction $tokenCalculator)
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
     * Purge all messages from a chat.
     */
    public function purgeChat(AiChat $chat): JsonResponse
    {
        $this->authorize('delete', $chat);

        $chat->messages()->delete();
        $chat->delete();

        return response()->json([
            'message' => 'Chat with messages deleted successfully.'
        ]);
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
