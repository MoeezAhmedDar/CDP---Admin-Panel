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
        Schema::create('gobatell_diagnostic_reports', function (Blueprint $table) {
            $table->id();
            $table->string('storelocation')->nullable();
            $table->string('store_sku')->nullable();
            $table->string('product')->nullable();
            $table->string('compliance_code')->nullable();
            $table->string('supplier_sku')->nullable();
            $table->string('pos_equivalent_grams')->nullable();
            $table->string('compliance_weight')->nullable();
            $table->string('opening_inventory')->nullable();
            $table->string('purchases_from_suppliers_additions')->nullable();
            $table->string('returns_from_customers_additions')->nullable();
            $table->string('other_additions_additions')->nullable();
            $table->string('sales_reductions')->nullable();
            $table->string('destruction_reductions')->nullable();
            $table->string('theft_reductions')->nullable();
            $table->string('returns_to_suppliers_reductions')->nullable();
            $table->string('other_reductions_reductions')->nullable();
            $table->string('closing_inventory')->nullable();
            $table->string('product_url')->nullable();
            $table->string('inventory_transactions_url')->nullable();

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
        Schema::dropIfExists('gobatell_diagnostic_reports');
    }
};
