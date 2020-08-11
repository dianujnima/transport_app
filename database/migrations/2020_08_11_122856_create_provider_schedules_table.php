<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProviderSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('provider_schedules', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('provider_id');
            $table->bigInteger('category_id');
            $table->string('route_from');
            $table->string('route_to');
            $table->date('date');
            $table->dateTime('arrival_time');
            $table->dateTime('destination_time');
            $table->double('ticket_cost');
            $table->text('image')->nullable();
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
        Schema::dropIfExists('provider_schedules');
    }
}
