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
        Schema::table('retailer_statements', function (Blueprint $table) {
            $table->string('average_price', 255)->nullable();
            $table->string('opening_inventory_units', 255)->nullable();
            $table->string('closing_inventory_units', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('retailer_statements', function (Blueprint $table) {
            $table->dropColumn('average_price');
            $table->dropColumn('opening_inventory_units');
            $table->dropColumn('closing_inventory_units');
        });
    }
};
