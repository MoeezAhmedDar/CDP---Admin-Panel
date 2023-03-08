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
        Schema::create('cova_sales_summary_reports', function (Blueprint $table) {
            $table->id();
            $table->string('product')->nullable();
            $table->string('sku')->nullable();
            $table->string('category')->nullable();
            $table->string('unit')->nullable();
            $table->string('items_sold')->nullable();
            $table->string('items_ref')->nullable();
            $table->string('net_qty')->nullable();
            $table->string('gross_sales')->nullable();
            $table->string('sub_total')->nullable();
            $table->string('total_Cost')->nullable();
            $table->string('gross_profit')->nullable();
            $table->string('gross_margin')->nullable();
            $table->string('total_margin')->nullable();
            $table->string('markdown_percentage')->nullable();
            $table->string('average_retail_price')->nullable();
            $table->foreignId('cova_diagnostic_report_id')->nullable()->constrained('cova_diagnostic_reports')->onDelete('cascade');
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
        Schema::dropIfExists('cova_sales_summary_reports');
    }
};
