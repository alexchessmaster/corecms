<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingTimeSlotsTable extends Migration
{
    public function up()
    {
        Schema::create('booking_time_slots', function (Blueprint $table) {
            $table->id();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('max_capacity')->default(2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['start_time', 'end_time']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_time_slots');
    }
}
