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
        Schema::create('ductie_diagnostic_reports', function (Blueprint $table) {
            $table->id();
            $table->string('location')->nullable();
            $table->string('transfer_from')->nullable();
            $table->string('sku')->nullable();
            $table->string('upcgtin')->nullable();
            $table->string('provincial_sku')->nullable();
            $table->string('product')->nullable();
            $table->string('product_category')->nullable();
            $table->string('package_id')->nullable();
            $table->string('external_package_id')->nullable();
            $table->string('received_on')->nullable();
            $table->string('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->string('unit_cost')->nullable();
            $table->string('total_cost')->nullable();
            $table->string('shipping_costs')->nullable();
            $table->string('discount_amount')->nullable();
            $table->string('total_product_grams')->nullable();
            $table->string('unittax')->nullable();
            $table->string('cultivationunittax')->nullable();
            $table->string('vendor')->nullable();
            $table->string('title')->nullable();
            $table->string('order_id')->nullable();
            $table->string('status')->nullable();
            $table->string('fullname')->nullable();
            $table->string('variable')->nullable();
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
        Schema::dropIfExists('ductie_diagnostic_reports');
    }
};
