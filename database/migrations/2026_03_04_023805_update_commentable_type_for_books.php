<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('commentables')
            ->where('commentable_type', 'App\Models\Book')
            ->update(['commentable_type' => 'App\Modules\Books\Models\Book']);
    }

    public function down()
    {
        DB::table('commentables')
            ->where('commentable_type', 'App\Modules\Books\Models\Book')
            ->update(['commentable_type' => 'App\Models\Book']);
    }
};
