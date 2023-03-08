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
        Schema::create('gobatell_sales_summary_report_retailers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id');
            $table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
            $table->foreignId('gb_sales_id')->constrained('gobatell_sales_summary_reports')->onDelete('cascade');
            $table->string('province')->nullable();
            $table->string('location')->nullable();
            $table->date('date');
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
        Schema::dropIfExists('gobatell_sales_summary_report_retailers');
    }
};
