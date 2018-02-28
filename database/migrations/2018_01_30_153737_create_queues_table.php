<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQueuesTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id')->unique();
            $table->unsignedInteger('branch_service_id');
            $table->integer('ticket_serving_now')->nullable();
            $table->integer('wait_time');
            $table->integer('total_ticket');
            $table->integer('pending_ticket');
            $table->datetime('start_time');
            $table->datetime('end_time')->nullable();
            $table->integer('active');
            $table->integer('avg_wait_time')->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('queues');
        Schema::enableForeignKeyConstraints(); 
    }
}
