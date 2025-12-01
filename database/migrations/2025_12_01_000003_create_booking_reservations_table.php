<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingReservationsTable extends Migration
{
public function up()
    {
        Schema::create('booking_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('booking_time_slot_id')->constrained('booking_time_slots')->onDelete('cascade');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'expired'])->default('pending');
            $table->timestamp('expires_at')->nullable(); // For 15-minute hold
            
            // Guest booking information
            $table->string('name')->nullable(); // Required for all bookings
            $table->string('email')->nullable(); // Required for confirmation
            $table->string('mobile_number')->nullable(); // Required contact
            $table->integer('age')->nullable(); // Optional
            $table->string('service')->nullable(); // What service they're booking
            $table->text('comments')->nullable(); // Additional notes from customer
            
            $table->timestamps();
            
            $table->index(['user_id', 'status']);
            $table->index(['booking_time_slot_id', 'status']);
            $table->index('expires_at');
            $table->index('email');
            $table->index('mobile_number');
        });
    }

    public function down()
    {
        Schema::dropIfExists('booking_reservations');
    }
}