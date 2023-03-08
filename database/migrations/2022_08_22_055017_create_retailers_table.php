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
        Schema::create('retailers', function (Blueprint $table) {
            $table->id();
            $table->string('corporate_name', 255)->nullable();
            $table->string('DBA', 255)->nullable();
            $table->string('owner_phone_number', 255)->nullable();
            $table->enum('aggregated_data', ['Yes', 'No', ''])->nullable()->default('No');
            $table->enum('status', ['Requested', 'Pending', 'Approved', 'Rejected']);
            $table->integer('report_count')->nullable();
            $table->softDeletes();

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
        Schema::dropIfExists('retailers');
    }
};
