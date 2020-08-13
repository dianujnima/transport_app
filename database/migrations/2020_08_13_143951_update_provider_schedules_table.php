<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProviderSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('provider_schedules', function($table){
            $table->enum('type', ['super_luxury', 'luxury', 'standard'])->default('standard')->after('category_id');
            $table->integer('total_seats')->nullable()->after('type');
            $table->text('desc')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('provider_schedules', function($table){
            $table->dropColumn('type');
            $table->dropColumn('total_seats');
            $table->dropColumn('desc');
        });
    }
}
