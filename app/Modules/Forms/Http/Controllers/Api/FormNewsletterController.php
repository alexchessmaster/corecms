<?php

namespace App\Modules\Forms\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Modules\Forms\Http\Requests\StoreFormNewsletterRequest;
use App\Modules\Forms\Http\Requests\UpdateFormNewsletterRequest;
use App\Modules\Forms\Models\FormNewsletter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FormNewsletterController extends Controller
{
    /**
     * Subscribe to newsletter
     */
    public function store(StoreFormNewsletterRequest $request): JsonResponse
    {

            // Check if email already exists
            $existingNewsletter = FormNewsletter::where('email', $request->email)->first();

            if ($existingNewsletter) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email already subscribed',
                    'error_code' => 'EMAIL_ALREADY_EXISTS'
                ], 400);
            }

            $newsletter = FormNewsletter::create([
                'email' => $request->email,
                'name' => $request->name,
                'status' => 'pending',
                'verification_token' => FormNewsletter::generateVerificationToken(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'locale' => $request->locale ?? app()->getLocale(),
            ]);

            // TODO: Send verification email to the subscriber

            return response()->json([
                'status' => 'success',
                'message' => 'Subscribed successfully',
                'data' => [
                    'email' => $newsletter->email,
                    'status' => $newsletter->status,
                ]
            ], 201);

    }

    /**
     * Verify newsletter subscription
     */
    public function verify(Request $request, string $token): JsonResponse
    {
        $newsletter = FormNewsletter::where('verification_token', $token)
            ->where('status', 'pending')
            ->first();

        if (!$newsletter) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired verification token.'
            ], 404);
        }

        $newsletter->markAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Your email has been successfully verified. Thank you for subscribing!',
            'data' => [
                'email' => $newsletter->email,
                'status' => $newsletter->status,
                'verified_at' => $newsletter->verified_at,
            ]
        ], 200);
    }

    /**
     * Unsubscribe from newsletter
     */
    public function unsubscribe(Request $request, string $email): JsonResponse
    {
        $newsletter = FormNewsletter::where('email', $email)
            ->whereIn('status', ['active', 'pending'])
            ->first();

        if (!$newsletter) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found in our newsletter list.'
            ], 404);
        }

        $newsletter->unsubscribe();

        return response()->json([
            'success' => true,
            'message' => 'You have been successfully unsubscribed from our newsletter.',
            'data' => [
                'email' => $newsletter->email,
                'status' => $newsletter->status,
            ]
        ], 200);
    }

    /**
     * Check subscription status
     */
    public function status(Request $request, string $email): JsonResponse
    {
        $newsletter = FormNewsletter::where('email', $email)->first();

        if (!$newsletter) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found in our newsletter list.',
                'data' => [
                    'subscribed' => false,
                ]
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'email' => $newsletter->email,
                'status' => $newsletter->status,
                'subscribed' => $newsletter->status === 'active',
                'verified' => $newsletter->isVerified(),
                'subscribed_at' => $newsletter->created_at,
            ]
        ], 200);
    }
}
