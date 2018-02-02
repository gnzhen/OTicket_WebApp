<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('servings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unique();
            $table->unsignedBigInteger('ticket_id')->index();
            $table->string('staff_username');
            $table->unsignedInteger('counter_id');
            $table->bigInteger('serve_time');
            $table->bigInteger('done_time');
            $table->timestamps();

            // $table->foreign('ticket_id')->references('id')->on('tickets');
            // $table->foreign('staff_username')->references('username')->on('users');
            // $table->foreign('counter_id')->references('id')->on('counters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('servings');
        Schema::enableForeignKeyConstraints(); 
    }
}
