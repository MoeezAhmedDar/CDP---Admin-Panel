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
        Schema::create('saskatchewan_provincial_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            $table->string('sku')->nullable();
            $table->string('producer_type')->nullable();
            $table->string('origin')->nullable();
            $table->string('brand')->nullable();
            $table->string('product_name')->nullable();
            $table->string('gtin')->nullable();
            $table->string('strain')->nullable();
            $table->string('size_grams')->nullable();
            $table->string('thc')->nullable();
            $table->text('cbd')->nullable();
            $table->string('terp')->nullable();
            $table->string('pack_date')->nullable();
            $table->string('per_unit_cost')->nullable();
            $table->string('qtycase')->nullable();
            $table->string('case_price')->nullable();
            $table->string('cases_available')->nullable();
            $table->string('quantity_of_cases_to_order')->nullable();
            $table->string('total')->nullable();
            $table->string('invest_per_gram')->nullable();
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
        Schema::dropIfExists('saskatchewan_provincial_catalogs');
    }
};
