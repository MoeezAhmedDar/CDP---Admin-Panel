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
        Schema::create('gobatell_sales_summary_reports', function (Blueprint $table) {
            $table->id();
            $table->string('sales_date')->nullable();
            $table->string('compliance_code')->nullable();
            $table->string('supplier_sku')->nullable();
            $table->string('opening_inventory')->nullable();
            $table->string('opening_inventory_value')->nullable();
            $table->string('purchases_from_suppliers_additions')->nullable();
            $table->string('purchases_from_suppliers_value')->nullable();
            $table->string('returns_from_customers_additions')->nullable();
            $table->string('customer_returns_retail_value')->nullable();
            $table->string('other_additions_additions')->nullable();
            $table->string('other_additions_value')->nullable();
            $table->string('sales_reductions')->nullable();
            $table->string('sold_retail_value')->nullable();
            $table->string('destruction_reductions')->nullable();
            $table->string('destruction_value')->nullable();
            $table->string('theft_reductions')->nullable();
            $table->string('theft_value')->nullable();
            $table->string('returns_to_suppliers_reductions')->nullable();
            $table->string('supplier_return_value')->nullable();
            $table->string('other_reductions_reductions')->nullable();
            $table->string('other_reductions_value')->nullable();
            $table->string('closing_inventory')->nullable();
            $table->string('closing_inventory_value')->nullable();
            $table->foreignId('gb_diagnostic_report_id')->nullable()->constrained('gobatell_diagnostic_reports')->onDelete('cascade');
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
        Schema::dropIfExists('gobatell_sales_summary_reports');
    }
};
