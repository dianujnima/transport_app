<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatCostToSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('schedule_seats', function (Blueprint $table) {
            $table->double('cost')->default(0)->after('total_seats');
        });

        Schema::table('provider_schedules', function (Blueprint $table) {
            $table->dropColumn('ticket_cost')->default(0)->after('total_seats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedule_seats', function (Blueprint $table) {
            $table->dropColumn('cost');
        });

        Schema::table('provider_schedules', function (Blueprint $table) {
            $table->double('ticket_cost')->default(0)->after('destination_time');
        });
    }
}
