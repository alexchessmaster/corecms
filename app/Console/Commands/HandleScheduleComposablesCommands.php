<?php

namespace App\Console\Commands;

use App\Modules\Articles\Models\Article;
use App\Modules\Books\Models\Book;
use App\Modules\News\Models\News;
use App\Modules\Products\Models\Product;
use Illuminate\Console\Command;

class HandleScheduleComposablesCommands extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'handle-schedule';

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
        $composables = [Product::class, Book::class, News::class, Article::class];

        foreach ($composables as $composable) {
            $composable::where('status', 'scheduled')
                ->where('scheduled_at', '<=', now())
                ->update(['status' => 'published']);
        }
    }
}
