<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 512);
            $table->string('type', 512);
            $table->bigInteger('organizer_id')->unsigned()->index();
            $table->text('description')->nullable();
            $table->string('image', 512)->nullable();
            $table->string('sub_title', 512)->nullable();
            $table->string('website', 512)->nullable();
            $table->tinyInteger('is_page')->default('0');
            $table->string('email', 512)->nullable();
            $table->string('phone', 512)->nullable();
            $table->string('facebook', 512)->nullable();
            $table->string('instagram', 512)->nullable();
            $table->string('twitter', 512)->nullable();
            $table->string('linkedin', 512)->nullable();
            $table->boolean('status')->default('1');
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tags', function (Blueprint $table) {
            $table->dropForeign(['organizer_id']);
        });

       Schema::dropIfExists('tags');

    }
}
