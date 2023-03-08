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
        Schema::create('ductie_sales_summary_reports', function (Blueprint $table) {
            $table->id();
            $table->string('locationname')->nullable();
            $table->string('serialnumber')->nullable();
            $table->string('productid')->nullable();
            $table->string('sku')->nullable();
            $table->string('product')->nullable();
            $table->string('productcategory')->nullable();
            $table->string('mastercategory')->nullable();
            $table->string('unit')->nullable();
            $table->string('dayssupply')->nullable();
            $table->string('inventorystart')->nullable();
            $table->string('coststart')->nullable();
            $table->string('received')->nullable();
            $table->string('costreceived')->nullable();
            $table->string('transferredin')->nullable();
            $table->string('costtransferredin')->nullable();
            $table->string('allocated')->nullable();
            $table->string('costallocated')->nullable();
            $table->string('sold')->nullable();
            $table->string('costsold')->nullable();
            $table->string('transferredout')->nullable();
            $table->string('costtransferredout')->nullable();
            $table->string('returned')->nullable();
            $table->string('costreturned')->nullable();
            $table->string('adjup')->nullable();
            $table->string('costadjup')->nullable();
            $table->string('adjdown')->nullable();
            $table->string('costadjdown')->nullable();
            $table->string('inventoryend')->nullable();
            $table->string('costend')->nullable();
            $table->string('lastauditeddate')->nullable();
            $table->foreignId('dd_report_id')->nullable()->constrained('ductie_diagnostic_reports')->onDelete('cascade');
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
        Schema::dropIfExists('ductie_sales_summary_reports');
    }
};
