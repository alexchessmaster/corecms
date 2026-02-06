<?php

namespace App\Modules\Booking\Models;

use Carbon\Carbon;
use App\Models\User;
use App\Modules\Booking\Models\BookingTimeSlot;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingReservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booking_time_slot_id',
        'status',
        'expires_at',
        'name',
        'email',
        'mobile_number',
        'age',
        'service',
        'comments'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'age' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function timeSlot()
    {
        return $this->belongsTo(BookingTimeSlot::class, 'booking_time_slot_id');
    }

    public function isExpired()
    {
        return $this->expires_at && Carbon::now()->greaterThan($this->expires_at);
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['pending', 'confirmed']);
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
                    ->where('expires_at', '<', Carbon::now());
    }

    /**
     * Check if this is a guest booking (no registered user)
     */
    public function isGuestBooking()
    {
        return is_null($this->user_id);
    }
}
