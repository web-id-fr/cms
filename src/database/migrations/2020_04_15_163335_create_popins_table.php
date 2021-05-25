<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->boolean('status')->nullable();
            $table->text('image')->nullable();
            $table->longText('description')->nullable();
            $table->string('button_1_title')->nullable();
            $table->string('button_1_url')->nullable();
            $table->boolean('display_second_button')->nullable();
            $table->boolean('display_call_to_action')->nullable();
            $table->string('button_2_title')->nullable();
            $table->string('button_2_url')->nullable();
            $table->text('type')->nullable();
            $table->text('button_name')->nullable();
            $table->integer('delay')->nullable();
            $table->boolean('mobile_display')->nullable();
            $table->integer('max_display')->nullable();
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
        Schema::dropIfExists('popins');
    }
}
