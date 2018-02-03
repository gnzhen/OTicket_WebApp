<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBranchCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_counters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('id')->unique();
            $table->string('branch_id');
            $table->string('counter_id');
            $table->string('staff_username')->nullable();
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
        Schema::dropIfExists('branch_counters');
        Schema::enableForeignKeyConstraints(); 
    }
}
