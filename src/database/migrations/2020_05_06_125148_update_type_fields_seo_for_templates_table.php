<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTypeFieldsSeoForTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn([
                'metatitle',
                'metadescription',
                'opengraph_title',
                'opengraph_description',
            ]);
        });

        Schema::table('templates', function(Blueprint $table) {
            $table->json('metatitle')->nullable();
            $table->json('metadescription')->nullable();
            $table->json('opengraph_title')->nullable();
            $table->json('opengraph_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('templates', function (Blueprint $table) {
            $table->dropColumn([
                'metatitle',
                'metadescription',
                'opengraph_title',
                'opengraph_description',
            ]);
        });

        Schema::table('templates', function(Blueprint $table) {
            $table->string('metatitle')->nullable();
            $table->string('metadescription')->nullable();
            $table->string('opengraph_title')->nullable();
            $table->string('opengraph_description')->nullable();
        });
    }
}
