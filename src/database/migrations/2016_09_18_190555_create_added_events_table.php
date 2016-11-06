<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddedEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('added_events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('device');
            $table->tinyInteger('direction');
            $table->integer('pxt_user_id');
            $table->timestamp('event_time');
            $table->timestamps();

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
        Schema::dropIfExists('added_events');
    }
}
