<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTaxTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tax_ticket', function (Blueprint $table) {
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');
            $table->foreign('tax_id')->references('id')->on('taxes')->onDelete('cascade');
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
            $table->dropForeign(['ticket_id', 'tax_id']);
        });
    }
}
