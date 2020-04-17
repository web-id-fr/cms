<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlideSlideshowTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('slide_slideshow', function (Blueprint $table) {
            $table->bigInteger('slideshow_id')->unsigned();
            $table->foreign('slideshow_id')->references('id')->on('slideshows')->onDelete('cascade');
            $table->bigInteger('slide_id')->unsigned();
            $table->foreign('slide_id')->references('id')->on('slides')->onDelete('cascade');
            $table->integer('order')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('slide_slideshow');
    }
}
