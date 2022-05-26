<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AlterUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function(Blueprint $table)
		{
			$table->string('avatar')->nullable()->default('users/default.png');
            $table->text('settings', 65535)->nullable();
			$table->integer('role_id')->unsigned()->nullable()->index('users_role_id_foreign');
			$table->string('organisation', 256)->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
