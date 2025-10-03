<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingTimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'start_time',
        'end_time',
        'max_capacity',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function reservations()
    {
        return $this->hasMany(BookingReservation::class);
    }

    public function confirmedReservations()
    {
        return $this->hasMany(BookingReservation::class)->where('status', 'confirmed');
    }

    public function availableCapacity()
    {
        return $this->max_capacity - $this->confirmedReservations()->count();
    }

    public function isAvailable()
    {
        return $this->is_active && $this->availableCapacity() > 0;
    }
}