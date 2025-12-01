<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\GenerateTimeSlotsFromTemplatesJob;


Schedule::command('redirects:remove-duplicate')->everyMinute()->withoutOverlapping();
Schedule::command('redirects:unchain')->everyMinute()->withoutOverlapping();
Schedule::command('sitemap:generate')->daily();
Schedule::command('backup:database')->daily();
Schedule::command('booking:release-expired')->everyMinute();
Schedule::job(new GenerateTimeSlotsFromTemplatesJob())->daily();
