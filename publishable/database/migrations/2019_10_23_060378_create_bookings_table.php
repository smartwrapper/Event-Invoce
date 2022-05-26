<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookingsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookings', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('customer_id')->unsigned();
			$table->integer('organiser_id')->unsigned()->nullable();
			$table->integer('event_id')->unsigned();
			$table->integer('ticket_id')->unsigned();
			$table->integer('quantity')->unsigned();
			$table->decimal('price', 10)->unsigned();
			$table->decimal('tax', 10)->nullable();
			$table->decimal('net_price', 10)->nullable();
			$table->boolean('status')->default(0);
			$table->timestamps();
			$table->string('event_title', 256);
			$table->date('event_start_date')->nullable();
			$table->date('event_end_date')->nullable();
			$table->time('event_start_time')->nullable();
			$table->time('event_end_time')->nullable();
			$table->integer('event_repetitive')->unsigned()->default(0);
			$table->string('ticket_title', 256);
			$table->decimal('ticket_price', 10)->unsigned();
			$table->string('event_category', 256);
			$table->integer('booking_cancel')->nullable()->default(0);
			$table->bigInteger('item_sku')->unsigned()->default(0);
			$table->bigInteger('order_number')->unsigned()->default(0);
			$table->integer('transaction_id')->unsigned()->default(0);
			$table->string('customer_name', 256);
			$table->string('customer_email', 256);
			$table->string('currency', 5)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('bookings');
	}

}
