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
        Schema::table('tech_pos_reports', function (Blueprint $table) {
            $table->string('productname', 255)->nullable();
            $table->string('category', 255)->nullable();
            $table->string('categoryparent', 255)->nullable();
            $table->string('brand', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tech_pos_reports', function (Blueprint $table) {
            $table->dropColumn('productname');
            $table->dropColumn('category');
            $table->dropColumn('categoryparent');
            $table->dropColumn('brand');
        });
    }
};
