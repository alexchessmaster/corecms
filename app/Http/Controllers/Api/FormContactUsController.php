<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFormContactUsRequest;
use App\Http\Requests\UpdateFormContactUsRequest;
use App\Models\FormContactUs;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormContactUsController extends Controller
{
    /**
     * Store a new contact us message
     */
    public function store(StoreFormContactUsRequest $request): JsonResponse
    {
        try {
            $contactUs = FormContactUs::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message,
                'status' => 'new',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // TODO: Send notification email to admin
            // Mail::to(config('mail.admin_email'))->send(new ContactFormSubmitted($contactUs));
            
            // TODO: Send confirmation email to user
            // Mail::to($contactUs->email)->send(new ContactFormConfirmation($contactUs));

            return response()->json([
                'success' => true,
                'message' => 'Thank you for contacting us! We will get back to you soon.',
                'data' => [
                    'uuid' => $contactUs->uuid,
                    'name' => $contactUs->name,
                    'email' => $contactUs->email,
                    'subject' => $contactUs->subject,
                    'status' => $contactUs->status,
                    'submitted_at' => $contactUs->created_at,
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit your message. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Display a listing of contact messages (Admin only)
     */
    public function index(Request $request): JsonResponse
    {
        $query = FormContactUs::with('handledBy:id,name,email')
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->has('status') && in_array($request->status, ['new', 'read', 'responded', 'closed'])) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 15);
        $messages = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $messages,
        ], 200);
    }

    /**
     * Display a specific contact message
     */
    public function show(string $uuid): JsonResponse
    {
        $contactUs = FormContactUs::with('handledBy:id,name,email')
            ->where('uuid', $uuid)
            ->first();

        if (!$contactUs) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found.'
            ], 404);
        }

        // Mark as read if it's new
        $contactUs->markAsRead();

        return response()->json([
            'success' => true,
            'data' => $contactUs,
        ], 200);
    }

    /**
     * Update contact message status
     */
    public function updateStatus(Request $request, string $uuid): JsonResponse
    {
        $request->validate([
            'status' => 'required|in:new,read,responded,closed',
        ]);

        $contactUs = FormContactUs::where('uuid', $uuid)->first();

        if (!$contactUs) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found.'
            ], 404);
        }

        $userId = $request->user()?->id;

        switch ($request->status) {
            case 'read':
                $contactUs->markAsRead();
                break;
            case 'responded':
                $contactUs->markAsResponded($userId);
                break;
            case 'closed':
                $contactUs->markAsClosed($userId);
                break;
            default:
                $contactUs->update(['status' => $request->status]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data' => $contactUs->fresh(),
        ], 200);
    }

    /**
     * Delete a contact message
     */
    public function destroy(string $uuid): JsonResponse
    {
        $contactUs = FormContactUs::where('uuid', $uuid)->first();

        if (!$contactUs) {
            return response()->json([
                'success' => false,
                'message' => 'Contact message not found.'
            ], 404);
        }

        $contactUs->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact message deleted successfully.',
        ], 200);
    }

    /**
     * Get statistics for contact messages
     */
    public function statistics(): JsonResponse
    {
        $stats = [
            'total' => FormContactUs::count(),
            'new' => FormContactUs::where('status', 'new')->count(),
            'read' => FormContactUs::where('status', 'read')->count(),
            'responded' => FormContactUs::where('status', 'responded')->count(),
            'closed' => FormContactUs::where('status', 'closed')->count(),
            'today' => FormContactUs::whereDate('created_at', today())->count(),
            'this_week' => FormContactUs::whereBetween('created_at', [
                now()->startOfWeek(),
                now()->endOfWeek()
            ])->count(),
            'this_month' => FormContactUs::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ], 200);
    }
}
