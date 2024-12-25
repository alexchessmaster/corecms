<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RedirectRemoveDuplicateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redirects:remove-duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Temporarily disable ONLY_FULL_GROUP_BY to handle the query
        \DB::statement('SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode, "ONLY_FULL_GROUP_BY", ""))');

        // Find the IDs of the duplicate records to keep (the latest record for each 'from' and 'language')
        $latestIds = \DB::table('redirects')
            ->select(\DB::raw('MAX(id) as id'))
            ->groupBy('from', 'language')
            ->pluck('id');

        // Delete all records that are not in the list of latest IDs
        \DB::table('redirects')
            ->whereNotIn('id', $latestIds)
            ->delete();

        // Re-enable ONLY_FULL_GROUP_BY if necessary
        \DB::statement('SET SESSION sql_mode=(SELECT CONCAT(@@sql_mode, ",ONLY_FULL_GROUP_BY"))');
    }
}
