<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSchedulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('schedules', function(Blueprint $table)
		{
			$table->increments('id');
			$table->boolean('repetitive_type');
			$table->string('repetitive_days', 256)->nullable();
			$table->string('repetitive_dates', 256)->nullable();
			$table->date('from_date')->nullable();
			$table->date('to_date')->nullable();
			$table->time('from_time')->nullable();
			$table->time('to_time')->nullable();
			$table->integer('event_id');
			$table->integer('user_id');
			$table->boolean('status')->default(1);
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
		Schema::drop('schedules');
	}

}
