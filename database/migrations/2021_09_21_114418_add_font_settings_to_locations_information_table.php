<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFontSettingsToLocationsInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('locations_information', function (Blueprint $table) {
            $table->string('back_button_font')->nullable();
            $table->string('back_button_font_color')->nullable();
            $table->string('back_button_font_size')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('locations_information', function (Blueprint $table) {
            $table->dropColumn('back_button_font');
            $table->dropColumn('back_button_font_color');
            $table->dropColumn('back_button_font_size');
        });
    }
}
