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
        Schema::create('retailer_report_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('retailer_id');
            $table->foreign('retailer_id')->references('id')->on('retailers')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Submited', 'Unsubmitted']);
            $table->string('province')->nullable();
            $table->string('location')->nullable();
            $table->date('date')->nullable();
            $table->string('pos')->nullable();
            $table->string('file1')->nullable();
            $table->string('file2')->nullable();
            $table->foreignId('address_id')->references('id')->on('retailer_addresses')->onDelete('cascade');
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
        Schema::dropIfExists('retailer_report_submissions');
    }
};
