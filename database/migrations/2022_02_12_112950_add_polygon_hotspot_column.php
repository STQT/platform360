<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPolygonHotspotColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspots_polygons', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotspot_id')->unsigned();
            $table->string('h');
            $table->string('v');
            $table->foreign('hotspot_id')->references('id')->on('hotspots');
            $table->timestamps();
        });

        Schema::table('hotspots', function (Blueprint $table) {
            $table->text('html_code')->nullable();
            $table->string('url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotspots_polygons');

        Schema::table('hotspots', function (Blueprint $table) {
            $table->dropColumn('html_code');
            $table->dropColumn('url');
        });
    }
}
