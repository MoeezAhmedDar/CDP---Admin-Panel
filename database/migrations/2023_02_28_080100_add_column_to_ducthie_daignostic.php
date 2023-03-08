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
        Schema::table('ductie_diagnostic_reports', function (Blueprint $table) {
            $table->string('datepurchashed', 255)->nullable();
            $table->string('datereceived', 255)->nullable();
            $table->string('item', 255)->nullable();
            $table->string('quantitypurchased', 255)->nullable();
            $table->string('quantityreceived', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ductie_diagnostic_reports', function (Blueprint $table) {
            $table->dropColumn('datepurchashed', 255);
            $table->dropColumn('datereceived', 255);
            $table->dropColumn('item', 255);
            $table->dropColumn('quantitypurchased', 255);
            $table->dropColumn('quantityreceived', 255);
        });
    }
};
