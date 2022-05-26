<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddAdminTaxToCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->decimal('admin_tax', 10, 2)->default(0);
            $table->boolean('settled')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('commissions', function (Blueprint $table) {
            Schema::dropColumn('admin_tax');
            Schema::dropColumn('settled');
        });
    }
}
