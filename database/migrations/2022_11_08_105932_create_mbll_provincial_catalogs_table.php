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
        Schema::create('mbll_provincial_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('order_qty_cases')->nullable();
            $table->string('list_type')->nullable();
            $table->string('skumbll_item_number')->nullable();
            $table->string('upcgtin')->nullable();
            $table->string('supplier')->nullable();
            $table->string('brand')->nullable();
            $table->string('type')->nullable();
            $table->string('sub_type')->nullable();
            $table->string('description1')->nullable();
            $table->string('thc_range')->nullable();
            $table->string('cbd_range')->nullable();
            $table->string('unit_volsize')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('units_per_case')->nullable();
            $table->string('case_price')->nullable();
            $table->string('product_notifications')->nullable();

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
        Schema::dropIfExists('mbll_provincial_catalogs');
    }
};
