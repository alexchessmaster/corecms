<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Modules\Booking\Jobs\GenerateTimeSlotsFromTemplatesJob;
use App\Modules\Shared\Jobs\DeployFrontendJob;

Schedule::command('redirects:remove-duplicate')->hourly()->withoutOverlapping();
Schedule::command('redirects:unchain')->hourly()->withoutOverlapping();
Schedule::command('sitemap:generate')->hourly()->then(function(){
    DeployFrontendJob::dispatch();
});
Schedule::command('backup:database')->daily();
Schedule::command('booking:release-expired')->everyMinute();
Schedule::job(new GenerateTimeSlotsFromTemplatesJob())->daily();
