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
        Schema::create('lps', function (Blueprint $table) {
            $table->id();
            $table->string('DBA')->nullable();
            $table->string('primary_contact_name', 255)->nullable();
            $table->string('primary_contact_position', 255)->nullable();
            $table->string('primary_contact_phone', 255);
            $table->string('variable', 255)->nullable();
            $table->enum('status', ['Requested', 'Pending', 'Approved', 'Rejected']);
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
        Schema::dropIfExists('lps');
    }
};
