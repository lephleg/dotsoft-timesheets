<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyLookupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_lookup', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pxt_user_id');
            $table->integer('minutes');
            $table->date('date');
            $table->timestamps();

            $table->foreign('pxt_user_id')
                ->references('pxt_user_id')
                ->on('employees');

            $table->unique(['pxt_user_id', 'date'], 'user_daily_index');
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_lookup');
    }
}
