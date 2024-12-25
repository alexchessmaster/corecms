<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RedirectUnchainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redirects:unchain';

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
        // Process the redirects table in chunks to avoid loading all data into memory
        \DB::table('redirects')->orderBy('id')->chunk(1000, function ($redirects) {
            // Create a hashmap of "from:language" => "to" for quick lookups
            $redirectMap = [];
            foreach ($redirects as $redirect) {
                $redirectMap["{$redirect->from}:{$redirect->language}"] = [
                    'to' => $redirect->to,
                    'id' => $redirect->id,
                ];
            }

            // Iterate through each redirect
            foreach ($redirects as $redirect) {
                $keyFrom = "{$redirect->from}:{$redirect->language}";
                $keyTo = "{$redirect->to}:{$redirect->language}";

                // Check if there's a chain (A → B and B → A with the same language)
                if (
                    isset($redirectMap[$keyTo]) &&
                    $redirectMap[$keyTo]['to'] === $redirect->from
                ) {
                    // Remove the current redirect (assume we remove the later record)
                    \DB::table('redirects')
                        ->where('id', $redirect->id)
                        ->delete();

                    // Break the chain by removing the deleted record from the map
                    unset($redirectMap[$keyFrom]);
                    unset($redirectMap[$keyTo]);
                }
            }
        });
    }
}
