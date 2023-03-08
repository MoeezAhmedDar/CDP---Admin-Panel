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
        Schema::table('ductie_sales_summary_reports', function (Blueprint $table) {
            $table->string('solddate', 255)->nullable();
            $table->string('customertype', 255)->nullable();
            $table->string('quantitysold', 255)->nullable();
            $table->string('grosssales', 255)->nullable();
            $table->string('discount', 255)->nullable();
            $table->string('netsales', 255)->nullable();
            $table->string('avgpriceperunit', 255)->nullable();
            $table->string('taxapplied', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ductie_sales_summary_reports', function (Blueprint $table) {
            $table->dropColumn('solddate', 255);
            $table->dropColumn('customertype', 255);
            $table->dropColumn('quantitysold', 255);
            $table->dropColumn('grosssales', 255);
            $table->dropColumn('discount', 255);
            $table->dropColumn('netsales', 255);
            $table->dropColumn('avgpriceperunit', 255);
            $table->dropColumn('taxapplied', 255);
        });
    }
};
