<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TableRelations extends Migration
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

        // Schema::table('branch_services', function($table) {
        //     $table->foreign('branch_id')->references('id')->on('branches');
        //     $table->foreign('service_id')->references('id')->on('services');
        // });

        // Schema::table('branch_counters', function($table) {
        //     $table->foreign('branch_id')->references('id')->on('branches');
        //     $table->foreign('counter_id')->references('id')->on('counters');
        //     $table->foreign('staff_username')->references('username')->on('users');
        // });

        Schema::table('queues', function($table) {
            $table->foreign('branch_service_id')->references('id')->on('branch_services');
        });

        Schema::table('tickets', function($table) {
            $table->foreign('queue_id')->references('id')->on('queues');
            $table->foreign('customer_username')->references('username')->on('mobile_users');
        });

        Schema::table('servings', function($table) {
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('staff_username')->references('username')->on('users');
            $table->foreign('branch_counter_id')->references('id')->on('branch_counters');
        });

        Schema::table('branch_counter_queue', function($table) {
            $table->foreign('branch_counter_id')->references('id')->on('branch_counters');
            $table->foreign('queue_id')->references('id')->on('queues');
        });

        Schema::table('ticket_change', function($table) {
            $table->foreign('ticket_id')->references('id')->on('tickets');
            $table->foreign('change_id')->references('id')->on('changes');
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
