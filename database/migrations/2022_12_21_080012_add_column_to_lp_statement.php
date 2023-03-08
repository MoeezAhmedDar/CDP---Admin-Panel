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
        Schema::table('lp_statements', function (Blueprint $table) {
            $table->string('sold', 255)->nullable();
            $table->string('average_price', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lp_statements', function (Blueprint $table) {
            $table->dropColumn('sold');
            $table->dropColumn('average_price');
        });
    }
};
