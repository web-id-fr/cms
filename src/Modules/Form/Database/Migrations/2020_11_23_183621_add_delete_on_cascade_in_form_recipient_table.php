<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteOnCascadeInFormRecipientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_recipient', function (Blueprint $table) {
            $table->dropForeign('form_recipient_form_id_foreign');
            $table->dropForeign('form_recipient_recipient_id_foreign');
            $table->foreign('form_id')->references('id')->on('forms')->cascadeOnDelete();
            $table->foreign('recipient_id')->references('id')->on('recipients')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_recipient', function (Blueprint $table) {
            $table->dropForeign('form_recipient_form_id_foreign');
            $table->dropForeign('form_recipient_recipient_id_foreign');
            $table->foreign('form_id')->references('id')->on('forms');
            $table->foreign('recipient_id')->references('id')->on('recipients');
        });
    }
}
