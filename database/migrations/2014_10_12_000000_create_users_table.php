<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->engine = 'InnoDB';
            $table->increments('id')->unique();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('role_id');
            $table->string('branch_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
        
        // Schema::table('users', function($table) {
        //     $table->foreign('role_id')->references('id')->on('roles');
        //     $table->foreign('branch_id')->references('id')->on('branches');
        // });
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
