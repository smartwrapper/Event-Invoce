<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventTagTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_tag', function (Blueprint $table) {
            
            $table->integer('event_id')->unsigned();
            $table->unsignedBigInteger('tag_id');

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('event_tag', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropForeign(['tag_id']);
            
        });
        Schema::dropIfExists('event_tag');
        
    }
}
