<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotspotsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspots_images', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file');
            $table->integer('hotspot_id')->unsigned();
            $table->timestamps();

            $table->foreign('hotspot_id')->references('id')->on('hotspots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotspots_images');
    }
}
