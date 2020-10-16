<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UdpatedScheduleSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_seats', function($table){
            $table->dropColumn('seat_no');
            $table->integer('total_seats')->after('schedule_id');
            $table->string('seat_type', 100)->after('schedule_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_seats', function($table){
            $table->string('seat_no', 20)->after('schedule_id');
            $table->dropColumn('total_seats');
            $table->dropColumn('seat_type');
        });
    }
}
