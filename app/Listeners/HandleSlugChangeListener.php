<?php

namespace App\Listeners;

use App\Jobs\CreateRedirectOnSlugChangeJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleSlugChangeListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        CreateRedirectOnSlugChangeJob::dispatch();
    }
}
