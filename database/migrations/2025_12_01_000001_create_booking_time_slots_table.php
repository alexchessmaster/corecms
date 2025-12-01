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
            $table->foreignId('template_id')->nullable()->constrained('booking_slot_templates')->onDelete('set null');
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->integer('max_capacity')->default(2);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_manually_disabled')->default(false); // Admin removed this slot
            $table->text('admin_notes')->nullable(); // Why was it disabled/added manually
            $table->timestamps();

            $table->index(['start_time', 'end_time', 'is_active']);
            $table->index(['template_id', 'is_manually_disabled']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_time_slots');
    }
}
