<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBookingsTableAddTicketNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bookings', function($table){
            $table->string('ticket_no', 100)->after('id');
            $table->string('cancelled_reason')->nullable()->after('status');
            $table->dateTime('cancelled_at')->nullable()->after('booked_at');
            $table->double('total_amount')->after('user_id');
            $table->double('per_seat_amount')->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function($table){
            $table->dropColumn('ticket_no');
            $table->dropColumn('cancelled_reason');
            $table->dropColumn('cancelled_at');
            $table->dropColumn('total_amount');
            $table->dropColumn('per_seat_amount');
        });
    }
}
