<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteOnCascadeInFormServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_service', function (Blueprint $table) {
            $table->dropForeign('form_service_form_id_foreign');
            $table->dropForeign('form_service_service_id_foreign');
            $table->foreign('form_id')->references('id')->on('forms')->cascadeOnDelete();
            $table->foreign('service_id')->references('id')->on('services')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_service', function (Blueprint $table) {
            $table->dropForeign('form_service_form_id_foreign');
            $table->dropForeign('form_service_service_id_foreign');
            $table->foreign('form_id')->references('id')->on('forms');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }
}
