<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateEventsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('events', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('title', 256)->nullable();
			$table->text('description', 65535)->nullable();
			$table->text('faq', 65535)->nullable();
			$table->string('thumbnail', 256)->nullable();
			$table->string('poster', 256)->nullable();
			$table->text('images', 65535)->nullable();
			$table->text('video_link', 65535)->nullable();
			$table->string('venue', 256)->nullable();
			$table->string('address', 512)->nullable();
			$table->string('city', 256)->nullable();
			$table->string('state', 256)->nullable();
			$table->string('zipcode', 64)->nullable();
			$table->integer('country_id')->nullable();
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->time('start_time')->nullable();
			$table->time('end_time')->nullable();
			$table->boolean('repetitive')->nullable()->default(0);
			$table->boolean('featured')->nullable()->default(0);
			$table->boolean('status')->nullable()->default(1);
			$table->string('meta_title', 256)->nullable();
			$table->string('meta_keywords', 512)->nullable();
			$table->text('meta_description', 65535)->nullable();
			$table->integer('category_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->boolean('add_to_facebook')->nullable();
			$table->timestamps();
			$table->string('slug', 512);
			$table->boolean('price_type')->default(0);
			$table->string('latitude', 256)->nullable();
			$table->string('longitude', 256)->nullable();
			$table->bigInteger('item_sku')->unsigned()->default(0);
			$table->boolean('publish')->nullable()->default(0);
			$table->string('is_publishable', 512)->nullable();
			$table->boolean('merge_schedule')->default(0);
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('events');
	}

}
