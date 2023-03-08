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
        Schema::create('tech_pos_reports', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('openinventoryunits')->nullable();
            $table->string('openinventoryvalue')->nullable();
            $table->string('quantitypurchasedunits')->nullable();
            $table->string('quantitypurchasedvalue')->nullable();
            $table->string('costperunit')->nullable();
            $table->string('quantitytransferinunits')->nullable();
            $table->string('quantitytransferinvalue')->nullable();
            $table->string('returnsfromcustomersunits')->nullable();
            $table->string('returnsfromcustomersvalue')->nullable();
            $table->string('otheradditionsunits')->nullable();
            $table->string('otheradditionsvalue')->nullable();
            $table->string('quantitysoldunits')->nullable();
            $table->string('quantitysoldvalue')->nullable();
            $table->string('onlinequantitysoldunits')->nullable();
            $table->string('onlinequantitysoldvalue')->nullable();
            $table->string('quantitytransferoutunits')->nullable();
            $table->string('quantitytransferoutvalue')->nullable();
            $table->string('quantitydestroyedunits')->nullable();
            $table->string('quantitydestroyedvalue')->nullable();
            $table->string('quantitylosttheftunits')->nullable();
            $table->string('quantitylosttheftvalue')->nullable();
            $table->string('returnstodistributorunits')->nullable();
            $table->string('returnstodistributorvalue')->nullable();
            $table->string('otherreductionsunits')->nullable();
            $table->string('otherreductionsvalue')->nullable();
            $table->string('closinginventoryunits')->nullable();
            $table->string('closinginventoryvalue')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tech_pos_reports');
    }
};
