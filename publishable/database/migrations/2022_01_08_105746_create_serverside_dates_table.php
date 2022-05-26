<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServersideDatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('serverside_dates', function (Blueprint $table) {
            $table->id();
            $table->date('from_date');
            $table->json('dates');
            $table->unsignedInteger('event_id');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
			
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('serversite_dates');
    }
}
