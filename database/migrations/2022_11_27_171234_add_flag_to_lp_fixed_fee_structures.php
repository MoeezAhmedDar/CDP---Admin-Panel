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
        Schema::table('lp_fixed_fee_structures', function (Blueprint $table) {
            $table->string('flag')->nullable();
            $table->string('comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lp_fixed_fee_structures', function (Blueprint $table) {
            $table->dropColumn('flag');
            $table->dropColumn('comment');
        });
    }
};
