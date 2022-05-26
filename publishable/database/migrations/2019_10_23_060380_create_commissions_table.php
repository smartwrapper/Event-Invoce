<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCommissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commissions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('organiser_id')->unsigned();
			$table->integer('booking_id')->unsigned();
			$table->decimal('admin_commission', 10)->unsigned();
			$table->decimal('customer_paid', 10)->unsigned();
			$table->decimal('organiser_earning', 10)->unsigned();
			$table->boolean('transferred')->default(0);
			$table->string('month_year', 10);
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
		Schema::drop('commissions');
	}

}
