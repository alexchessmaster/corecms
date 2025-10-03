<?php

namespace App\Jobs;

use App\Models\BookingReservation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BookingReleaseExpiredJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $expiredTime = Carbon::now()->subMinutes(15);
        
        BookingReservation::where('created_at', '<', $expiredTime)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);
    }
}