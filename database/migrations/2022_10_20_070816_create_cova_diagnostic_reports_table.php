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
        Schema::create('cova_diagnostic_reports', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->string('type')->nullable();
            $table->string('aglc_sku')->nullable();
            $table->string('new_brunswick_sku')->nullable();
            $table->string('ocs_sku')->nullable();
            $table->string('ylc_sku')->nullable();
            $table->string('manitoba_barcode_upc')->nullable();
            $table->string('ontario_barcode_upc')->nullable();
            $table->string('saskatchewan_barcode_upc')->nullable();
            $table->text('link_to_product')->nullable();
            $table->string('opening_inventory_units')->nullable();
            $table->string('quantity_purchased_units')->nullable();
            $table->string('reductions_receiving_error_units')->nullable();
            $table->string('returns_from_customers_units')->nullable();
            $table->string('other_additions_units')->nullable();
            $table->string('quantity_sold_units')->nullable();
            $table->string('quantity_destroyed_units')->nullable();
            $table->string('quantity_lost_theft_units')->nullable();
            $table->string('returns_to_supplier_units')->nullable();
            $table->string('other_reductions_units')->nullable();
            $table->string('closing_inventory_units')->nullable();
            $table->string('variable')->nullable();
            $table->enum('check', ['0', '1'])->nullable();
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
        Schema::dropIfExists('cova_diagnostic_reports');
    }
};
