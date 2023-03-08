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
        Schema::create('ideal_sales_summary_reports', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('product_description')->nullable();
            $table->string('quantity_purchased')->nullable();
            $table->string('purchase_amount')->nullable();
            $table->string('return_quantity')->nullable();
            $table->string('amount_return')->nullable();
            $table->foreignId('ideal_diagnostic_report_id')->references('id')->on('ideal_diagnostic_reports');
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
        Schema::dropIfExists('ideal_sales_summary_reports');
    }
};
