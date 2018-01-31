<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unique();
            $table->string('ticket_no');
            $table->bigInteger('issue_time');
            $table->unsignedBigInteger('queue_id')->index();
            $table->integer('wait_time');
            $table->integer('ppl_ahead');
            $table->string('username');
            $table->integer('postponed');
            $table->timestamps();

            // $table->foreign('queue_id')->references('id')->on('queues');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
