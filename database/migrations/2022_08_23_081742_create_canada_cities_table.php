<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('canadacities', function (Blueprint $table) {

            $table->text('city');
            $table->text('city_ascii');
            $table->text('province_id');
            $table->text('province_name');
            $table->text('lat');
            $table->text('lng');
            $table->float('population');
            $table->float('density');
            $table->text('timezone');
            $table->integer('ranking');
            $table->text('postal');
            $table->text('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('canada_cities');
    }
};
