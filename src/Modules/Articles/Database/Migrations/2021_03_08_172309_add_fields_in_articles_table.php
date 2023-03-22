<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Webid\Cms\Modules\Articles\Models\Article;

class AddFieldsInArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->integer('order')->nullable();
            $table->boolean('not_display_in_list')->default(false);
            $table->integer('article_type')->default(Article::_TYPE_NORMAL);
            $table->longText('author')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropColumn('order');
            $table->dropColumn('not_display_in_list');
            $table->dropColumn('article_type');
            $table->dropColumn('author');
        });
    }
}
