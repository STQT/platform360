<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hotspots', function (Blueprint $table) {
            $table->string('instagram_url')->nullable()->change();
            $table->string('information_logo')->nullable()->change();
            $table->string('information_title')->nullable()->change();
            $table->string('information_description')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hotspots', function (Blueprint $table) {
            $table->string('instagram_url')->change();
            $table->string('information_logo')->change();
            $table->string('information_title')->change();
            $table->string('information_description')->change();
        });
    }
}
