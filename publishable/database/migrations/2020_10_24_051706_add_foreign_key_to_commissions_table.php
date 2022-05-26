<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('commissions', function (Blueprint $table) {
            $table->unsignedInteger('booking_id')->nullable()->change();
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
        Schema::enableForeignKeyConstraints();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropForeign(['booking_id']);
        });
    }
}
