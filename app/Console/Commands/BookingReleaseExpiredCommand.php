<?php

namespace App\Console\Commands;

use App\Modules\Booking\Models\BookingReservation;
use Carbon\Carbon;
use Illuminate\Console\Command;

class BookingReleaseExpiredCommand extends Command
{
    protected $signature = 'booking:release-expired';
    protected $description = 'Release expired booking time slots that have not been confirmed within the 15-minute window';

    public function handle()
    {
        $expiredTime = Carbon::now()->subMinutes(15);
        
        BookingReservation::where('created_at', '<', $expiredTime)
            ->where('status', 'pending')
            ->delete();

        $this->info('Expired bookings released successfully.');
    }
}
