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
        Schema::create('greenline_retailer_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id');
            $table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
            $table->foreignId('greenline_report_id')->constrained('greenline_reports')->onDelete('cascade');
            $table->string('province')->nullable();
            $table->string('location')->nullable();
            $table->date('date')->nullable();
            $table->string('pos')->nullable();
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
        Schema::dropIfExists('greenline_retailer_reports');
    }
};
