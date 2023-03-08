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
        Schema::create('profit_tech_reports', function (Blueprint $table) {
            $table->id();
            $table->string('product_sku')->nullable();
            $table->string('opening_inventory_units')->nullable();
            $table->string('opening_inventory_value')->nullable();
            $table->string('quantity_purchased_units')->nullable();
            $table->string('quantity_purchased_value')->nullable();
            $table->string('quantity_purchased_units_transfer')->nullable();
            $table->string('quantity_purchased_value_transfer')->nullable();
            $table->string('returns_from_customers_units')->nullable();
            $table->string('returns_from_customers_value')->nullable();
            $table->string('other_additions_units')->nullable();
            $table->string('other_additions_value')->nullable();
            $table->string('quantity_sold_instore_units')->nullable();
            $table->string('quantity_sold_instore_value')->nullable();
            $table->string('quantity_sold_online_units')->nullable();
            $table->string('quantity_sold_online_value')->nullable();
            $table->string('quantity_sold_units_transfer')->nullable();
            $table->string('quantity_sold_value_transfer')->nullable();
            $table->string('quantity_destroyed_units')->nullable();
            $table->string('quantity_destroyed_value')->nullable();
            $table->string('quantity_losttheft_units')->nullable();
            $table->string('quantity_losttheft_value')->nullable();
            $table->string('returns_to_aglc_units')->nullable();
            $table->string('returns_to_aglc_value')->nullable();
            $table->string('other_reductions_units')->nullable();
            $table->string('other_reductions_value')->nullable();
            $table->string('closing_inventory_units')->nullable();
            $table->string('closing_inventory_value')->nullable();
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
        Schema::dropIfExists('profit_tech_reports');
    }
};
