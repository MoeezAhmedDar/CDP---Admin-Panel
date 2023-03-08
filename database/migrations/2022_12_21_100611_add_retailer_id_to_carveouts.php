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
        Schema::table('carve_outs', function (Blueprint $table) {
            $table->foreignId('retailer_id')->nullable()->constrained('retailers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carve_outs', function (Blueprint $table) {
            $table->dropColumn('retailer_id');
        });
    }
};
