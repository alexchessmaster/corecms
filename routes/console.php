<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();

Schedule::command('redirects:remove-duplicate')->everyMinute()->withoutOverlapping();
Schedule::command('redirects:unchain')->everyMinute()->withoutOverlapping();
// TODO add exception from sitemap to article
// TODO Generate SITEMAP job
// TODO db backup
Schedule::command('sitemap:generate')->daily();
