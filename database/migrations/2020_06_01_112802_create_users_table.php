<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->text('image')->nullable();
            $table->string('username')->unique();
            $table->string('email')->nullable();
            $table->enum('signup_type', ['facebook', 'google', 'web', 'api'])->default('api');
            $table->string('password');
            $table->boolean('is_active')->default('1');
            $table->string('phone_no', 40)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('state', 80)->nullable();
            $table->string('zipcode', 80)->nullable();
            $table->string('country', 80)->default('pakistan');
            $table->rememberToken();
            $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('users');
    }
}
