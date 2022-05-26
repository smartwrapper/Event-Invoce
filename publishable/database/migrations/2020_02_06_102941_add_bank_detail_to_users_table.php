<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankDetailToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bank_name', 512)->nullable();
            $table->string('bank_code', 512)->nullable();
            $table->string('bank_branch_name', 512)->nullable();
            $table->string('bank_branch_code', 512)->nullable();
            $table->string('bank_account_number', 512)->nullable();
            $table->string('bank_account_name', 512)->nullable();
            $table->string('bank_account_phone', 512)->nullable();
            $table->string('address', 512)->nullable();
            $table->string('phone', 512)->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'bank_name',
                'bank_code',
                'bank_branch_name',
                'bank_branch_code',
                'bank_account_number',
                'bank_account_name',
                'bank_account_phone',
                'address',
                'phone',
            ]);
        });
    }
}
