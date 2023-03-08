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
            $table->string('lp_legal_name')->after('id')->nullable();
            $table->string('lp_doing_business_as')->after('lp_legal_name')->nullable();
            $table->string('comments')->after('cost')->nullable();
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
            $table->dropColumn('lp_legal_name');
            $table->dropColumn('lp_doing_business_as');
            $table->dropColumn('comments');
        });
    }
};
