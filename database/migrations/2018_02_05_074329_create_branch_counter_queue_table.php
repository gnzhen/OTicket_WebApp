<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchCounterQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_counter_queue', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->unsignedInteger('branch_counter_id');
            $table->unsignedBigInteger('queue_id')->index();
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
        Schema::dropIfExists('branch_counter_queue');
        Schema::enableForeignKeyConstraints();
    }
}
