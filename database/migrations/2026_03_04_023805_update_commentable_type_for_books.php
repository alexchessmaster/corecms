<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('widgetables')
            ->where('widgetable_type', 'App\Models\Book')
            ->update(['widgetable_type' => 'App\Modules\Books\Models\Book']);
    }

    public function down()
    {
        DB::table('widgetables')
            ->where('widgetable_type', 'App\Modules\Books\Models\Book')
            ->update(['widgetable_type' => 'App\Models\Book']);
    }
};
