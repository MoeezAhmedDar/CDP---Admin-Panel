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
        Schema::create('clean_sheets', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('product_name')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->string('sold')->nullable();
            $table->string('purchased')->nullable();
            $table->string('average_price')->nullable();
            $table->string('average_cost')->nullable();
            $table->string('barcode')->nullable();
            $table->string('variable')->nullable();
            $table->foreignId('retailerReportSubmission_id');
            $table->foreign('retailerReportSubmission_id')->references('id')->on('retailer_report_submissions')->onDelete('cascade');

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
        Schema::dropIfExists('clean_sheets');
    }
};
