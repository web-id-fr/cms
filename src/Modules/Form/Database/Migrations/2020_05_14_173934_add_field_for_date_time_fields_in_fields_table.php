<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldForDateTimeFieldsInFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->string('date_field_title')->nullable();
            $table->string('date_field_placeholder')->nullable();
            $table->string('time_field_title')->nullable();
            $table->string('time_field_placeholder')->nullable();
            $table->string('duration_field_title')->nullable();
            $table->string('field_name_time')->nullable();
            $table->string('field_name_duration')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->dropColumn('date_field_title');
            $table->dropColumn('date_field_placeholder');
            $table->dropColumn('time_field_title');
            $table->dropColumn('time_field_placeholder');
            $table->dropColumn('duration_field_title');
            $table->dropColumn('field_name_time');
            $table->dropColumn('field_name_duration');
        });
    }
}
