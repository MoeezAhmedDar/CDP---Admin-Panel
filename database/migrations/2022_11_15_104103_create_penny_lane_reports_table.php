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
        Schema::create('penny_lane_reports', function (Blueprint $table) {
            $table->id();
            $table->string('store')->nullable();
            $table->string('product_sku')->nullable();
            $table->string('description')->nullable();
            $table->string('uom')->nullable();
            $table->string('category')->nullable();
            $table->string('opening_inventory_units')->nullable();
            $table->string('opening_inventory_value')->nullable();
            $table->string('quantity_purchased_units')->nullable();
            $table->string('quantity_purchased_value')->nullable();
            $table->string('returns_from_customers_units')->nullable();
            $table->string('returns_from_customers_value')->nullable();
            $table->string('other_additions_units')->nullable();
            $table->string('other_additions_value')->nullable();
            $table->string('quantity_sold_units')->nullable();
            $table->string('quantity_sold_value')->nullable();
            $table->string('transfer_units')->nullable();
            $table->string('transfer_value')->nullable();
            $table->string('returns_to_vendor_units')->nullable();
            $table->string('returns_to_vendor_value')->nullable();
            $table->string('inventory_adjustment_units')->nullable();
            $table->string('inventory_adjustment_value')->nullable();
            $table->string('destroyed_units')->nullable();
            $table->string('destroyed_value')->nullable();
            $table->string('closing_inventory_units')->nullable();
            $table->string('closing_inventory_value')->nullable();
            $table->string('min_stock')->nullable();
            $table->string('low_inv')->nullable();
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
        Schema::dropIfExists('penny_lane_reports');
    }
};
