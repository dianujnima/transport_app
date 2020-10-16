<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('per_seat_amount');
            $table->dropColumn('seat_ids');
            $table->dropColumn('seat_nos');
        });

        Schema::create('booking_seats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('booking_id');
            $table->bigInteger('schedule_id');
            $table->integer('total_seats');
            $table->double('seat_cost', 100)->default(0);
            $table->double('total_cost', 100)->default(0);
        });

        Schema::table('schedule_seats', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->double('per_seat_amount')->after('user_id');
            $table->text('seat_ids')->after('per_seat_amount');
            $table->text('seat_nos')->after('seat_ids');
        });
        Schema::dropIfExists('booking_seats');

        Schema::table('schedule_seats', function (Blueprint $table) {
            $table->enum('status', ['booked','hold','available','cancelled'])->after('cost');
        });
    }
}
