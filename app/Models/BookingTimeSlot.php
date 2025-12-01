<?php

namespace App\Models;

use App\Models\BookingReservation;
use App\Models\BookingSlotTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookingTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'template_id',
        'start_time',
        'end_time',
        'max_capacity',
        'is_active',
        'is_manually_disabled',
        'admin_notes'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
        'is_manually_disabled' => 'boolean'
    ];

    public function template()
    {
        return $this->belongsTo(BookingSlotTemplate::class, 'template_id');
    }

    public function reservations()
    {
        return $this->hasMany(BookingReservation::class, 'booking_time_slot_id');
    }

    public function confirmedReservations()
    {
        return $this->hasMany(BookingReservation::class, 'booking_time_slot_id')->where('status', 'confirmed');
    }

    public function availableCapacity()
    {
        return $this->max_capacity - $this->confirmedReservations()->count();
    }

    public function isAvailable()
    {
        return $this->is_active 
            && !$this->is_manually_disabled 
            && $this->availableCapacity() > 0;
    }
}