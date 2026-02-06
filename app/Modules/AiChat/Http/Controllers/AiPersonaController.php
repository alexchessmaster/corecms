<?php

namespace App\Modules\AiChat\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAiPersonaRequest;
use App\Http\Requests\UpdateAiPersonaRequest;
use App\Modules\AiChat\Models\AiPersona;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AiPersonaController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', AiPersona::class);
        $personas = AiPersona::where(function ($query) use ($request) {
                // Show public personas
                $query->where('is_public', true)
                      ->where('is_active', true);

                // Also show user's own personas if authenticated
                if ($request->user()) {
                    $query->orWhere('created_by_user_id', $request->user()->id);
                }
            })
            ->select('id', 'name', 'description', 'suggested_model', 'is_public', 'created_by_user_id', 'created_at')
            ->orderBy('is_public', 'desc') // Public personas first
            ->orderBy('name')
            ->paginate(50);

        return response()->json($personas);
    }

    /**
     * Get user's own personas only.
     */
    public function myPersonas(Request $request): JsonResponse
    {
        $this->authorize('viewAny', AiPersona::class);
        if (!$request->user()) {
            return response()->json(['error' => 'Authentication required'], 401);
        }

        $personas = AiPersona::where('created_by_user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($personas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAiPersonaRequest $request): JsonResponse
    {
        $this->authorize('create', AiPersona::class);

        $persona = AiPersona::create([
            'name' => $request->name,
            'description' => $request->description,
            'system_prompt' => $request->system_prompt,
            'suggested_model' => $request->suggested_model ?? 'gpt-3.5-turbo',
            'default_parameters' => $request->default_parameters,
            'created_by_user_id' => $request->user()?->id,
            'is_public' => $request->is_public ?? false,
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Persona created successfully',
            'persona' => $persona
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AiPersona $aiPersona): JsonResponse
    {
        $this->authorize('view', $aiPersona);
        // Check if user can view this persona
        if (!$aiPersona->is_public && $aiPersona->created_by_user_id !== auth()->id()) {
            return response()->json(['error' => 'Persona not found'], 404);
        }

        return response()->json($aiPersona);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AiPersona $aiPersona)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAiPersonaRequest $request, AiPersona $aiPersona): JsonResponse
    {
        $this->authorize('update', $aiPersona);
        // Check if user owns this persona
        if ($aiPersona->created_by_user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $aiPersona->update($request->validated());

        return response()->json([
            'message' => 'Persona updated successfully',
            'persona' => $aiPersona
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AiPersona $aiPersona): JsonResponse
    {
        $this->authorize('delete', $aiPersona);

        // Check if persona is being used in any active chats
        $activeChats = $aiPersona->chats();
        // if ($activeChatCount > 0) {
        //     return response()->json([
        //         'error' => 'Cannot delete persona that is being used in chats',
        //         'active_chats' => $activeChatCount
        //     ], 409);
        // }
        foreach ($activeChats as $chat) {
            $chat->ai_persona_id = null; // Or set to a default persona ID if desired
            $chat->save();
        }

        $aiPersona->delete();

        return response()->json([
            'message' => 'Persona deleted successfully'
        ]);
    }

    /**
     * Toggle persona active status.
     */
    public function toggleActive(AiPersona $aiPersona): JsonResponse
    {
        // Check if user owns this persona
        if ($aiPersona->created_by_user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $aiPersona->update(['is_active' => !$aiPersona->is_active]);

        return response()->json([
            'message' => 'Persona status updated',
            'is_active' => $aiPersona->is_active
        ]);
    }

    /**
     * Duplicate an existing persona.
     */
    public function duplicate(AiPersona $aiPersona, Request $request): JsonResponse
    {
        // Check if user can view the original persona
        if (!$aiPersona->is_public && $aiPersona->created_by_user_id !== auth()->id()) {
            return response()->json(['error' => 'Persona not found'], 404);
        }

        $newPersona = AiPersona::create([
            'name' => $request->name ?? $aiPersona->name . ' (Copy)',
            'description' => $aiPersona->description,
            'system_prompt' => $aiPersona->system_prompt,
            'suggested_model' => $aiPersona->suggested_model,
            'default_parameters' => $aiPersona->default_parameters,
            'created_by_user_id' => auth()->id(),
            'is_public' => false, // Copies are always private initially
            'is_active' => true,
        ]);

        return response()->json([
            'message' => 'Persona duplicated successfully',
            'persona' => $newPersona
        ], 201);
    }

    /**
     * Get popular public personas.
     */
    public function popular(): JsonResponse
    {
        $personas = AiPersona::where('is_public', true)
            ->where('is_active', true)
            ->withCount('chats') // Assuming you add this relationship
            ->orderBy('chats_count', 'desc')
            ->limit(10)
            ->get(['id', 'name', 'description', 'suggested_model']);

        return response()->json($personas);
    }

    /**
     * Search personas.
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');

        if (!$query) {
            return response()->json([]);
        }

        $personas = AiPersona::where(function ($queryBuilder) use ($request) {
                // Show public personas
                $queryBuilder->where('is_public', true)
                           ->where('is_active', true);

                // Also show user's own personas if authenticated
                if ($request->user()) {
                    $queryBuilder->orWhere('created_by_user_id', $request->user()->id);
                }
            })
            ->where(function ($queryBuilder) use ($query) {
                $queryBuilder->where('name', 'LIKE', "%{$query}%")
                           ->orWhere('description', 'LIKE', "%{$query}%");
            })
            ->select('id', 'name', 'description', 'suggested_model', 'is_public')
            ->limit(20)
            ->get();

        return response()->json($personas);
    }
}
