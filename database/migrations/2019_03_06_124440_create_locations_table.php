<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->string('number')->nullable();
            $table->text('description')->nullable();
            $table->string('working_hours')->nullable();
            $table->string('website')->nullable();
            $table->string('facebook')->nullable();
            $table->string('instagram')->nullable();
            $table->string('telegram')->nullable();
            $table->tinyInteger('isDefault')->default(0);
            $table->text('panorama')->nullable();
            $table->text('xmllocation');
            $table->string('is_sky')->nullable();
            $table->string('podlocparent_id')->nullable();
            $table->string('sky_id')->nullable();
            $table->integer('category_id');
            $table->longText('floors')->nullable();
            $table->string('icon')->nullable();
            $table->string('icon_svg')->nullable();
            $table->string('isfeatured')->nullable();
            $table->tinyInteger('isFloor')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('onmap')->nullable();
            $table->string('skymainforcity')->nullable();
            $table->string('slug');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('locations');
    }
}
