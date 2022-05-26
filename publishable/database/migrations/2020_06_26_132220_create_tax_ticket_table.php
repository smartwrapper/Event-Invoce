<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_ticket', function (Blueprint $table) {
            $table->unsignedInteger('ticket_id');
            $table->unsignedInteger('tax_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tax_ticket', function (Blueprint $table) {
            Schema::drop('tax_ticket');
        });
    }
}
