<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteOnCascadeForPopinPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('popin_template', function (Blueprint $table) {
            $table->dropForeign('popin_template_popin_id_foreign');
            $table->dropForeign('popin_template_template_id_foreign');
            $table->foreign('popin_id')->references('id')->on('popins')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('popin_template', function (Blueprint $table) {
            $table->dropForeign('popin_template_popin_id_foreign');
            $table->dropForeign('popin_template_template_id_foreign');
            $table->foreign('popin_id')->references('id')->on('popins');
            $table->foreign('template_id')->references('id')->on('templates');
        });
    }
}
