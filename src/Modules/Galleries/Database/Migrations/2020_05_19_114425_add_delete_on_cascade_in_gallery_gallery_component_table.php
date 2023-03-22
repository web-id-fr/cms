<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDeleteOnCascadeInGalleryGalleryComponentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gallery_gallery_component', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('gallery_gallery_component_gallery_id_foreign');
                $table->dropForeign('gallery_gallery_component_gallery_component_id_foreign');
            }
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
            $table->foreign('gallery_component_id')->references('id')->on('galleries_component')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gallery_gallery_component', function (Blueprint $table) {
            if (DB::getDriverName() !== 'sqlite') {
                $table->dropForeign('gallery_gallery_component_gallery_id_foreign');
                $table->dropForeign('gallery_gallery_component_gallery_component_id_foreign');
            }
            $table->foreign('gallery_id')->references('id')->on('galleries');
            $table->foreign('gallery_component_id')->references('id')->on('galleries_component');
        });
    }
}
