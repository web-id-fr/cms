<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipientServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipient_service', function (Blueprint $table) {
            $table->unsignedBigInteger('recipient_id');
            $table->foreign('recipient_id')->references('id')->on('recipients');
            $table->unsignedBigInteger('service_id');
            $table->foreign('service_id')->references('id')->on('services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipient_service');
    }
}
