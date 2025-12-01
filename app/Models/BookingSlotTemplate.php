<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSlotTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'days_of_week',
        'start_time',
        'end_time',
        'slot_duration_minutes',
        'max_capacity',
        'valid_from',
        'valid_until',
        'is_active',
        'description'
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'start_time' => 'datetime:H:i:s',
        'end_time' => 'datetime:H:i:s',
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
        'slot_duration_minutes' => 'integer',
        'max_capacity' => 'integer'
    ];

    public function timeSlots()
    {
        return $this->hasMany(BookingTimeSlot::class, 'template_id');
    }

    /**
     * Check if template is valid for a given date
     */
    public function isValidForDate($date)
    {
        $carbonDate = \Carbon\Carbon::parse($date);
        
        return $this->is_active 
            && $carbonDate->between($this->valid_from, $this->valid_until)
            && in_array($carbonDate->dayOfWeekIso, $this->days_of_week);
    }

    /**
     * Get all time slots that should be generated from this template for a given date
     */
    public function generateTimeSlotsForDate($date)
    {
        if (!$this->isValidForDate($date)) {
            return collect();
        }

        $slots = collect();
        $carbonDate = \Carbon\Carbon::parse($date);
        
        // Extract time from the datetime objects
        $startTime = $carbonDate->copy()->setTimeFrom($this->start_time);
        $endTime = $carbonDate->copy()->setTimeFrom($this->end_time);

        while ($startTime->lessThan($endTime)) {
            $slotEnd = $startTime->copy()->addMinutes($this->slot_duration_minutes);
            
            if ($slotEnd->lessThanOrEqualTo($endTime)) {
                $slots->push([
                    'template_id' => $this->id,
                    'start_time' => $startTime->copy(),
                    'end_time' => $slotEnd->copy(),
                    'max_capacity' => $this->max_capacity,
                    'is_active' => true,
                    'is_manually_disabled' => false
                ]);
            }
            
            $startTime = $slotEnd;
        }

        return $slots;
    }
}
