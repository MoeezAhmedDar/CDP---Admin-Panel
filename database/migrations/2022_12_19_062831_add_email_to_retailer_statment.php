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
            $table->string('carve_out')->nullable();
            $table->string('retailer_name', 255)->nullable();
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
            $table->dropColumn('retailerEmail');
            $table->dropColumn('carve_out');
            $table->dropColumn('retailer_name');
        });
    }
};
