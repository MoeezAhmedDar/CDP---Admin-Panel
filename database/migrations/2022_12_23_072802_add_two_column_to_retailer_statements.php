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
            $table->foreignId('lp_id')->nullable()->constrained('lps');
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
            $table->dropColumn('lp_id');
        });
    }
};
