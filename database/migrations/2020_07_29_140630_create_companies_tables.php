<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salons', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('city_id')->default(1);
            $table->bigInteger('manager_id');
            $table->string('name');
            $table->string('slug');
            $table->text('image')->nullable();
            $table->text('phones')->nullable();
            $table->string('address', 80);
            $table->string('city', 80);
            $table->string('state', 80);
            $table->string('zipcode', 80);
            $table->string('country', 80);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('timings')->nullable();
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
        Schema::dropIfExists('salons');
    }
}
