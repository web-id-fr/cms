<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormFieldsForMenuCustomItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_custom_items', function(Blueprint $table) {
            $table->integer('type_link')->nullable();
            $table->integer('form_id')->nullable();
            $table->string('target')->default('_self')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_custom_items', function(Blueprint $table) {
            $table->dropColumn('type_link');
            $table->dropColumn('form_id');
            $table->string('target')->default('_self')->nullable(false)->change();
        });
    }
}
