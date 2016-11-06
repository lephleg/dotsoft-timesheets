<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pxt_event_id')->unique();
            $table->integer('device');
            $table->integer('direction');
            $table->integer('pxt_user_id');
            $table->timestamp('event_time');

            $table->foreign('pxt_user_id')
                ->references('pxt_user_id')
                ->on('employees');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
