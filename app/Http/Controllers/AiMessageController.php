<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAiMessageRequest;
use App\Http\Requests\UpdateAiMessageRequest;
use App\Models\AiMessage;
use App\Models\AiChat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiMessageController extends Controller
{
    /**
     * Display messages for a specific chat.
     */
    public function index(Request $request, AiChat $aiChat): JsonResponse
    {
        $messages = $aiChat->messages()
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Store a newly created message (manual message addition).
     */
    public function store(StoreAiMessageRequest $request, AiChat $aiChat): JsonResponse
    {
        $message = $aiChat->messages()->create([
            'role' => $request->role,
            'content' => $request->content,
            'input_tokens' => $request->input_tokens,
            'output_tokens' => $request->output_tokens,
            'message_cost_usd' => $request->message_cost_usd,
            'metadata' => $request->metadata,
        ]);

        // Update chat totals if tokens are provided
        if ($request->input_tokens || $request->output_tokens) {
            $aiChat->increment('total_input_tokens', $request->input_tokens ?? 0);
            $aiChat->increment('total_output_tokens', $request->output_tokens ?? 0);
            $aiChat->increment('total_cost_usd', $request->message_cost_usd ?? 0);
        }

        return response()->json([
            'message' => 'Message created successfully',
            'data' => $message
        ], 201);
    }

    /**
     * Display the specified message.
     */
    public function show(AiMessage $aiMessage): JsonResponse
    {
        // Check if user has access to this message's chat
        $chat = $aiMessage->aiChat;
        if ($chat->user_id && $chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($aiMessage->load('aiChat:id,session_name'));
    }

    /**
     * Update the specified message (for editing content).
     */
    public function update(UpdateAiMessageRequest $request, AiMessage $aiMessage): JsonResponse
    {
        // Check if user has access to this message's chat
        $chat = $aiMessage->aiChat;
        if ($chat->user_id && $chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Only allow editing user messages or system messages
        if ($aiMessage->role === 'assistant') {
            return response()->json(['error' => 'Cannot edit AI assistant messages'], 422);
        }

        $aiMessage->update($request->validated());

        return response()->json([
            'message' => 'Message updated successfully',
            'data' => $aiMessage
        ]);
    }

    /**
     * Remove the specified message.
     */
    public function destroy(AiMessage $aiMessage): JsonResponse
    {
        // Check if user has access to this message's chat
        $chat = $aiMessage->aiChat;
        if ($chat->user_id && $chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update chat totals by subtracting this message's tokens/cost
        if ($aiMessage->input_tokens || $aiMessage->output_tokens) {
            $chat->decrement('total_input_tokens', $aiMessage->input_tokens ?? 0);
            $chat->decrement('total_output_tokens', $aiMessage->output_tokens ?? 0);
            $chat->decrement('total_cost_usd', $aiMessage->message_cost_usd ?? 0);
        }

        $aiMessage->delete();

        return response()->json([
            'message' => 'Message deleted successfully'
        ]);
    }

    /**
     * Get messages by role for a specific chat.
     */
    public function getByRole(Request $request, AiChat $aiChat, string $role): JsonResponse
    {
        $validRoles = ['user', 'assistant', 'system'];
        
        if (!in_array($role, $validRoles)) {
            return response()->json(['error' => 'Invalid role'], 422);
        }

        $messages = $aiChat->messages()
            ->where('role', $role)
            ->orderBy('created_at', 'asc')
            ->paginate(50);

        return response()->json($messages);
    }

    /**
     * Search messages within a chat.
     */
    public function search(Request $request, AiChat $aiChat): JsonResponse
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }

        $messages = $aiChat->messages()
            ->where('content', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get(['id', 'role', 'content', 'created_at']);

        return response()->json($messages);
    }

    /**
     * Get message statistics for a chat.
     */
    public function getStats(AiChat $aiChat): JsonResponse
    {
        $stats = [
            'total_messages' => $aiChat->messages()->count(),
            'user_messages' => $aiChat->messages()->where('role', 'user')->count(),
            'assistant_messages' => $aiChat->messages()->where('role', 'assistant')->count(),
            'system_messages' => $aiChat->messages()->where('role', 'system')->count(),
            'average_message_length' => $aiChat->messages()->avg(\DB::raw('LENGTH(content)')),
            'total_characters' => $aiChat->messages()->sum(\DB::raw('LENGTH(content)')),
        ];

        return response()->json($stats);
    }

    /**
     * Regenerate AI response for the last message.
     */
    public function regenerateResponse(Request $request, AiMessage $aiMessage): JsonResponse
    {
        // Check if this is an assistant message
        if ($aiMessage->role !== 'assistant') {
            return response()->json(['error' => 'Can only regenerate assistant messages'], 422);
        }

        // Check if user has access
        $chat = $aiMessage->aiChat;
        if ($chat->user_id && $chat->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // This would typically trigger a new AI response
        // For now, just mark it for regeneration
        $aiMessage->update([
            'metadata' => array_merge($aiMessage->metadata ?? [], [
                'regenerated_at' => now(),
                'regeneration_requested' => true
            ])
        ]);

        return response()->json([
            'message' => 'Message marked for regeneration',
            'data' => $aiMessage
        ]);
    }

    /**
     * Export messages from a chat.
     */
    public function export(AiChat $aiChat, Request $request)
    {
        $format = $request->get('format', 'json'); // json, txt, csv
        
        $messages = $aiChat->messages()
            ->orderBy('created_at', 'asc')
            ->get(['role', 'content', 'created_at']);

        switch ($format) {
            case 'txt':
                $content = $messages->map(function ($message) {
                    return "[{$message->created_at}] {$message->role}: {$message->content}";
                })->implode("\n\n");
                
                return response($content)
                    ->header('Content-Type', 'text/plain')
                    ->header('Content-Disposition', 'attachment; filename="chat_messages.txt"');

            case 'csv':
                $headers = ['Role', 'Content', 'Created At'];
                $data = $messages->map(function ($message) {
                    return [$message->role, $message->content, $message->created_at];
                })->prepend($headers);

                $csv = $data->map(function ($row) {
                    return implode(',', array_map(function ($field) {
                        return '"' . str_replace('"', '""', $field) . '"';
                    }, $row));
                })->implode("\n");

                return response($csv)
                    ->header('Content-Type', 'text/csv')
                    ->header('Content-Disposition', 'attachment; filename="chat_messages.csv"');

            default:
                return response()->json($messages);
        }
    }
}
