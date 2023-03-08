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
        Schema::create('retailer_statements', function (Blueprint $table) {
            $table->id();
            $table->string('lp')->nullable();
            $table->string('product')->nullable();
            $table->string('sku')->nullable();
            $table->string('barcode')->nullable();
            $table->string('quantity')->nullable();
            $table->string('quantity_sold')->nullable();
            $table->string('unit_cost')->nullable();
            $table->string('total_purchase_cost')->nullable();
            $table->string('fee_per')->nullable();
            $table->string('fee_in_dollar')->nullable();
            $table->string('ircc_per')->nullable();
            $table->string('ircc_dollar')->nullable();
            $table->string('total_fee')->nullable();
            $table->string('variable')->nullable();
            $table->foreignId('retailerReportSubmission_id')->nullable()->constrained('retailer_report_submissions');
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
        Schema::dropIfExists('retailer_statements');
    }
};
