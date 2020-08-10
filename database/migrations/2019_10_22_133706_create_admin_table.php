<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->text('image')->nullable();
            $table->string('username')->unique();
            $table->string('email')->nullable();
            $table->string('user_type', 80)->default('admin');
            $table->text('user_roles')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default('1');
            $table->bigInteger('added_by_id')->nullable();
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
        Schema::dropIfExists('admins');
    }
}
