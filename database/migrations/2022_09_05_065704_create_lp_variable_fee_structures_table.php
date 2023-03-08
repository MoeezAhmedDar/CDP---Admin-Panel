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
        Schema::create('lp_variable_fee_structures', function (Blueprint $table) {
            $table->id();
            $table->string('province', 255);
            $table->string('category', 255);
            $table->string('brand', 255);
            $table->string('product_name', 255);
            $table->string('provincial');
            $table->string('GTin', 255);
            $table->string('product');
            $table->string('thc');
            $table->string('cbd')->nullable();
            $table->string('case');
            $table->string('unit_cost', 255);
            $table->date('offer', 255);
            $table->date('offer_end', 255);
            $table->string('data');
            $table->text('comments')->nullable();
            $table->text('links')->nullable();
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
        Schema::dropIfExists('lp_variable_fee_structures');
    }
};
