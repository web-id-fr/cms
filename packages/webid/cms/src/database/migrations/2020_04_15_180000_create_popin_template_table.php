<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePopinTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('popin_template', function (Blueprint $table) {
            $table->unsignedBigInteger('popin_id');
            $table->foreign('popin_id')->references('id')->on('popins');
            $table->unsignedBigInteger('template_id');
            $table->foreign('template_id')->references('id')->on('templates');
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
        Schema::dropIfExists('popin_template');
    }
}
