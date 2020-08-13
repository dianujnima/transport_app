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
            $table->bigInteger('user_id');
            $table->text('seat_ids');
            $table->text('seat_nos');
            $table->dateTime('booking_date');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_method')->nullable();
            $table->text('transaction_data')->nullable();
            $table->enum('status', ['booked','cancelled','hold'])->default('hold');
            $table->dateTime('booked_at')->nullable();
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
