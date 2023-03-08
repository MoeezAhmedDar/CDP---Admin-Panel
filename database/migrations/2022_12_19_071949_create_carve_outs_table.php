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
        Schema::create('carve_outs', function (Blueprint $table) {
            $table->id();
            $table->string('retailer_name')->nullable();
            $table->string('email')->nullable();
            $table->string('carve_outs')->nullable();
            $table->string('location')->nullable();
            $table->string('lp')->nullable();
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
        Schema::dropIfExists('carve_outs');
    }
};
