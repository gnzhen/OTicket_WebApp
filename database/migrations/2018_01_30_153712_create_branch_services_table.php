<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_services', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id')->unique();
            $table->string('branch_id');
            $table->string('service_id');
            $table->integer('avg_wait_time');
            $table->integer('default_avg_wait_time');
            $table->timestamps();
        });

        // Schema::table('branch_services', function($table) {
        //     $table->foreign('branch_id')->references('id')->on('branches');
        //     $table->foreign('service_id')->references('id')->on('services');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch_services');
    }
}
