<?php

namespace App\Jobs;

use App\Models\BookingSlotTemplate;
use App\Models\BookingTimeSlot;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GenerateTimeSlotsFromTemplatesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $daysAhead;

    /**
     * Create a new job instance.
     *
     * @param int $daysAhead Number of days ahead to generate slots for (default: 90 days = 3 months)
     */
    public function __construct($daysAhead = 90)
    {
        $this->daysAhead = $daysAhead;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addDays($this->daysAhead);
        
        Log::info('Starting time slot generation', [
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);

        $templates = BookingSlotTemplate::where('is_active', true)
            ->where('valid_from', '<=', $endDate)
            ->where('valid_until', '>=', $startDate)
            ->get();

        $generatedCount = 0;
        $skippedCount = 0;

        foreach ($templates as $template) {
            $currentDate = max($startDate, Carbon::parse($template->valid_from));
            $templateEndDate = min($endDate, Carbon::parse($template->valid_until));

            while ($currentDate->lessThanOrEqualTo($templateEndDate)) {
                // Check if this day is in the template's days_of_week
                if (in_array($currentDate->dayOfWeekIso, $template->days_of_week)) {
                    $slotsForDate = $template->generateTimeSlotsForDate($currentDate);

                    foreach ($slotsForDate as $slotData) {
                        // Check if slot already exists and is not manually disabled
                        $existingSlot = BookingTimeSlot::where('start_time', $slotData['start_time'])
                            ->where('end_time', $slotData['end_time'])
                            ->first();

                        if ($existingSlot) {
                            // If slot exists and is manually disabled, skip it
                            if ($existingSlot->is_manually_disabled) {
                                $skippedCount++;
                                continue;
                            }
                            
                            // Update existing slot if needed
                            if ($existingSlot->max_capacity != $slotData['max_capacity'] || 
                                $existingSlot->template_id != $slotData['template_id']) {
                                $existingSlot->update([
                                    'max_capacity' => $slotData['max_capacity'],
                                    'template_id' => $slotData['template_id'],
                                ]);
                            }
                        } else {
                            // Create new slot
                            BookingTimeSlot::create($slotData);
                            $generatedCount++;
                        }
                    }
                }

                $currentDate->addDay();
            }
        }

        Log::info('Time slot generation completed', [
            'generated' => $generatedCount,
            'skipped' => $skippedCount,
            'templates_processed' => $templates->count(),
        ]);

        return [
            'generated' => $generatedCount,
            'skipped' => $skippedCount,
            'templates_processed' => $templates->count(),
        ];
    }
}
