<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingSlotTemplatesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('booking_slot_templates');
        Schema::dropIfExists('booking_reservations');
        Schema::dropIfExists('booking_time_slots');
        Schema::create('booking_slot_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Regular Weekday Schedule"
            $table->json('days_of_week'); // [1,2,3,4,5] for Mon-Fri (1=Monday, 7=Sunday)
            $table->time('start_time'); // e.g., 08:00:00
            $table->time('end_time'); // e.g., 16:00:00
            $table->integer('slot_duration_minutes')->default(60); // Duration of each slot in minutes
            $table->integer('max_capacity')->default(2); // Max bookings per slot
            $table->date('valid_from'); // Start date for generating slots
            $table->date('valid_until'); // End date for generating slots
            $table->boolean('is_active')->default(true); // Can disable template without deleting
            $table->text('description')->nullable(); // Optional notes for admin
            $table->timestamps();
            
            $table->index(['valid_from', 'valid_until', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_slot_templates');
    }
}
