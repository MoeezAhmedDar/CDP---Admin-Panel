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
        Schema::create('epos_reports', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('name')->nullable();
            $table->string('barcode')->nullable();
            $table->string('brand')->nullable();
            $table->string('compliance_category')->nullable();
            $table->string('opening')->nullable();
            $table->string('sold')->nullable();
            $table->string('purchased')->nullable();
            $table->string('closing')->nullable();
            $table->string('average_price')->nullable();
            $table->string('average_cost')->nullable();
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
        Schema::dropIfExists('epos_reports');
    }
};
