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
        Schema::create('retailer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id');
            $table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
            $table->string('street_number', 255)->nullable();
            $table->string('street_name', 255)->nullable();
            $table->string('postal_code', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('location', 255)->nullable();
            $table->string('province', 255)->nullable();
            $table->string('contact_person_name_at_location', 255)->nullable();
            $table->string('contact_person_phone_number_at_location', 255)->nullable();
            $table->string('pos')->nullable();
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
        Schema::dropIfExists('retailer_addresses');
    }
};
