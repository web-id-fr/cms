<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteOnCascadeInRecipientServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipient_service', function (Blueprint $table) {
            $table->dropForeign('recipient_service_recipient_id_foreign');
            $table->dropForeign('recipient_service_service_id_foreign');
            $table->foreign('recipient_id')->references('id')->on('recipients')->cascadeOnDelete();
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
        Schema::table('recipient_service', function (Blueprint $table) {
            $table->dropForeign('recipient_service_recipient_id_foreign');
            $table->dropForeign('recipient_service_service_id_foreign');
            $table->foreign('recipient_id')->references('id')->on('recipients');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }
}
