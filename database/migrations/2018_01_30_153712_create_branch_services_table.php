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
            $table->integer('avg_wait_time')->nullable();
            $table->integer('default_avg_wait_time');
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
        Schema::dropIfExists('branch_services');
        Schema::enableForeignKeyConstraints(); 
    }
}
