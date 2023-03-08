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
        Schema::create('lp_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lp_id');
            $table->foreign('lp_id')->references('id')->on('lps')->onDelete('cascade');
            $table->string('street_number', 255)->nullable();
            $table->string('street_name', 255)->nullable();
            $table->string('postal_code', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('province', 255)->nullable();

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
        Schema::dropIfExists('lp_addresses');
    }
};
