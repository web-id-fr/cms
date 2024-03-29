<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMenuDescriptionFieldInMenuCustomItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_custom_items', function (Blueprint $table) {
            $table->longText('menu_description')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_custom_items', function (Blueprint $table) {
            $table->longText('menu_description')->nullable(false)->change();
        });
    }
}
