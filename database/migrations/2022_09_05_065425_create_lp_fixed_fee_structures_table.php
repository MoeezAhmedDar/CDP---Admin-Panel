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
        Schema::create('lp_fixed_fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('province_id')->nullable();
            $table->string('product_description_and_size')->nullable();
            $table->string('pre_roll')->nullable();
            $table->string('brand')->nullable();
            $table->string('provincial_sku')->nullable();
            $table->string('gtin')->nullable();
            $table->string('data_fee')->nullable();
            $table->string('cost')->nullable();
            $table->foreignId('lp_id')->nullable();
            $table->foreign('lp_id')->references('id')->on('lps')->onDelete('cascade');

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
        Schema::dropIfExists('lp_fixed_fee_structures');
    }
};
