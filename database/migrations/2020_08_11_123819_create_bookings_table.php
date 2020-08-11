<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('schedule_id');
            $table->bigInteger('provider_id');
            $table->bigInteger('seat_id');
            $table->bigInteger('user_id');
            $table->string('seat_no', 20);
            $table->dateTime('booking_date');
            $table->string('transition_id')->nullable();
            $table->string('transition_method')->nullable();
            $table->text('transition_data')->nullable();
            $table->enum('status', ['booked','cancelled'])->default('booked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
