<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_change', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger('ticket_id')->index();
            $table->unsignedBigInteger('change_id')->index();
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
        Schema::dropIfExists('ticket_change');
        Schema::enableForeignKeyConstraints(); 
    }
}
