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
            $table->string('branch_service_id');
            $table->text('ticket_ids')->nullable();
            $table->integer('ticket_serving_now')->nullable();
            $table->text('counter_ids');
            $table->timestamps();

            // $table->foreign('branch_service_id')->references('id')->on('branch_services');
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
