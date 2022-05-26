<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTransactionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('transactions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->decimal('amount_paid', 10)->unsigned()->default(0.00);
			$table->bigInteger('item_sku')->unsigned()->default(0);
			$table->bigInteger('order_number')->unsigned()->default(0);
			$table->string('txn_id', 512)->nullable();
			$table->string('payer_reference', 512)->nullable();
			$table->string('currency_code', 512)->nullable();
			$table->string('payment_status', 512)->nullable();
			$table->string('payment_gateway', 128)->nullable();
			$table->boolean('status')->default(0);
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
		Schema::drop('transactions');
	}

}
