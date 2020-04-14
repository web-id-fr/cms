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
            $table->json('title');
            $table->json('slug')->nullable();
            $table->boolean('indexation')->default(0);
            $table->integer('status');
            $table->boolean('homepage')->default(false);
            // SEO
            $table->string('metatitle')->nullable();
            $table->string('metadescription')->nullable();
            $table->string('opengraph_title')->nullable();
            $table->string('opengraph_description')->nullable();
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
