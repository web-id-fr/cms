<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->longText('question');
            $table->longText('answer');
            $table->integer('order')->nullable();
            $table->integer('status');
            $table->unsignedBigInteger('faq_theme_id')->nullable();
            $table->foreign('faq_theme_id')->references('id')->on('faq_themes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faqs');
    }
}
