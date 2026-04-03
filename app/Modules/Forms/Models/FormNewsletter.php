<?php

namespace App\Modules\Forms\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class FormNewsletter extends Model
{
    /** @use HasFactory<\Database\Factories\FormNewsletterFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'status',
        'verification_token',
        'verified_at',
        'unsubscribed_at',
        'ip_address',
        'user_agent',
        'locale',
        'is_viewed_by_admin',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
        'is_viewed_by_admin' => 'boolean',
    ];

    /**
     * Generate a unique verification token
     */
    public static function generateVerificationToken(): string
    {
        return Str::random(64);
    }

    /**
     * Check if the subscription is verified
     */
    public function isVerified(): bool
    {
        return $this->verified_at !== null && $this->status === 'active';
    }

    /**
     * Mark as verified
     */
    public function markAsVerified(): void
    {
        $this->update([
            'verified_at' => now(),
            'status' => 'active',
            'verification_token' => null,
        ]);
    }

    /**
     * Unsubscribe
     */
    public function unsubscribe(): void
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }
}
