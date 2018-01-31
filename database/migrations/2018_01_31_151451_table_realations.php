<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableRealations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        Schema::table('users', function($table) {
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreign('branch_id')->references('id')->on('branches');
        });

        Schema::table('branch_services', function($table) {
            $table->foreign('branch_id')->references('id')->on('branches');
            $table->foreign('service_id')->references('id')->on('services');
        });

        Schema::table('queues', function($table) {
            $table->foreign('branch_service_id')->references('id')->on('branch_services');
        });
        
        Schema::table('counters', function($table) {
            $table->foreign('branch_service_id')->references('id')->on('branch_services');
            $table->foreign('staff_username')->references('username')->on('users');
        });

        Schema::table('tickets', function($table) {
            $table->foreign('queue_id')->references('id')->on('queues');
        });

        Schema::table('servings', function($table) {
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('staff_username')->references('username')->on('users');
            $table->foreign('counter_id')->references('id')->on('counters');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
