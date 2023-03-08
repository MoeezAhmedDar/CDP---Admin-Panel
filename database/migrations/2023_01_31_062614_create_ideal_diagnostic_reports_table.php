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
        Schema::create('ideal_diagnostic_reports', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('description')->nullable();
            $table->string('opening')->nullable();
            $table->string('purchases')->nullable();
            $table->string('returns')->nullable();
            $table->string('trans_in')->nullable();
            $table->string('trans_out')->nullable();
            $table->string('unit_sold')->nullable();
            $table->string('write_offs')->nullable();
            $table->string('closing')->nullable();
            $table->string('net_sales_ex')->nullable();
            $table->string('variable')->nullable();
            $table->foreignId('retailerReportSubmission_id')->nullable()->constrained('retailer_report_submissions')->onDelete('cascade');
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
        Schema::dropIfExists('ideal_diagnostic_reports');
    }
};
