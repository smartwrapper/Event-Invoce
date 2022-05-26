<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToBannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->integer('order')->nullable()->default(0);
            $table->string('button_url')->nullable();
            $table->string('button_title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('banners', function (Blueprint $table) {
            Schema::dropColumn('order');
            Schema::dropColumn('button_url');
            Schema::dropColumn('button_title');
        });
    }
}
