<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('title');
            $table->longText('slug')->nullable();
            $table->boolean('indexation')->default(0);
            $table->integer('status');
            $table->boolean('homepage')->default(false);
            // SEO
            $table->longText('metatitle')->nullable();
            $table->longText('metadescription')->nullable();
            $table->longText('opengraph_title')->nullable();
            $table->longText('opengraph_description')->nullable();
            $table->string('opengraph_picture')->nullable();
            $table->dateTime('publish_at')->nullable();
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
        Schema::dropIfExists('templates');
    }
}
