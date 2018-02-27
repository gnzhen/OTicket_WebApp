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
            $table->datetime('issue_time');
            $table->datetime('disposed_time')->nullable();
            $table->unsignedBigInteger('queue_id')->index();
            $table->integer('wait_time');
            $table->integer('ppl_ahead');
            $table->unsignedInteger('mobile_user_id')->nullable();
            $table->integer('postponed');
            $table->string('status');
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
        Schema::dropIfExists('tickets');
        Schema::enableForeignKeyConstraints(); 
    }
}
