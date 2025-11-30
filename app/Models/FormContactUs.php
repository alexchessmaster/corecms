<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FormContactUs extends Model
{
    /** @use HasFactory<\Database\Factories\FormContactUsFactory> */
    use HasFactory;

    protected $table = 'form_contact_us';

    protected $fillable = [
        'uuid',
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'handled_by_user_id',
        'handled_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'handled_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the user who handled this contact
     */
    public function handledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handled_by_user_id');
    }

    /**
     * Mark as read
     */
    public function markAsRead(): void
    {
        if ($this->status === 'new') {
            $this->update(['status' => 'read']);
        }
    }

    /**
     * Mark as responded
     */
    public function markAsResponded(int $userId = null): void
    {
        $this->update([
            'status' => 'responded',
            'handled_by_user_id' => $userId,
            'handled_at' => now(),
        ]);
    }

    /**
     * Mark as closed
     */
    public function markAsClosed(int $userId = null): void
    {
        $this->update([
            'status' => 'closed',
            'handled_by_user_id' => $userId ?? $this->handled_by_user_id,
            'handled_at' => $this->handled_at ?? now(),
        ]);
    }

    /**
     * Scope for new messages
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for unhandled messages
     */
    public function scopeUnhandled($query)
    {
        return $query->whereIn('status', ['new', 'read']);
    }
}
