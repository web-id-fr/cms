<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('article_image')->nullable();
            $table->integer('status');
            $table->text('extrait')->nullable();
            $table->longText('content')->nullable();

            // SEO
            $table->boolean('indexation')->default(false);
            $table->boolean('follow')->default(false);
            $table->string('metatitle')->nullable();
            $table->string('metadescription')->nullable();
            $table->string('opengraph_title')->nullable();
            $table->string('opengraph_description')->nullable();
            $table->string('opengraph_picture')->nullable();

            // Timestamps
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
        Schema::dropIfExists('articles');
    }
}
