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
        Schema::create('lp_statements', function (Blueprint $table) {
            $table->id();
            $table->string('provice')->nullable();
            $table->string('retailer')->nullable();
            $table->string('category')->nullable();
            $table->string('product')->nullable();
            $table->string('sku')->nullable();
            $table->string('opening_inventory_units')->nullable();
            $table->string('closing_inventory_units')->nullable();
            $table->string('total_sales_quantity')->nullable();
            $table->string('quantity_purchased')->nullable();
            $table->string('unit_cost')->nullable();
            $table->string('total_purchased_cost')->nullable();
            $table->string('total_fee_percentage')->nullable();
            $table->string('total_fee_dollars')->nullable();
            $table->string('variable')->nullable();
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
        Schema::dropIfExists('lp_statements');
    }
};
