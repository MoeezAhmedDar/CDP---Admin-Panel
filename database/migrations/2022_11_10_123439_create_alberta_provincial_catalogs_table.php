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
        Schema::create('alberta_provincial_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('aglc_sku')->nullable();
            $table->string('quantity')->nullable();
            $table->string('sku_description')->nullable();
            $table->string('available_cases')->nullable();
            $table->string('format')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('thc_min')->nullable();
            $table->string('thc_max')->nullable();
            $table->string('thc_uom')->nullable();
            $table->string('cbd_min')->nullable();
            $table->string('cbd_max')->nullable();
            $table->string('cbd_uom')->nullable();
            $table->string('eachespercase')->nullable();
            $table->string('new_sku_this_week')->nullable();
            $table->string('on_sale')->nullable();
            $table->string('sell_price_per_case')->nullable();
            $table->string('orginal_price_per_case')->nullable();
            $table->string('sell_price_per_unit')->nullable();
            $table->string('msrp')->nullable();
            $table->string('recycle_fees_per_case')->nullable();
            $table->string('deposit_fee_per_case')->nullable();
            $table->string('company_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('product_name')->nullable();
            $table->text('long_product_description')->nullable();
            $table->string('merchandising_strategy')->nullable();
            $table->string('type_sub_2_category')->nullable();
            $table->string('strain_sub_3_category')->nullable();
            $table->string('province_of_origin')->nullable();
            $table->string('region_of_product_optional')->nullable();
            $table->string('extraction_process')->nullable();
            $table->string('dominant_terpene_1')->nullable();
            $table->string('dominant_terpene_1_content')->nullable();
            $table->string('dominant_terpene_2')->nullable();
            $table->string('dominant_terpene_2_content')->nullable();
            $table->string('dominant_terpene_3')->nullable();
            $table->string('dominant_terpene_3_content')->nullable();
            $table->longText('other_terpenes_list')->nullable();
            $table->string('net_content')->nullable();
            $table->string('content_uom')->nullable();
            $table->string('piece_qty')->nullable();
            $table->string('piece_size')->nullable();
            $table->string('dce_g')->nullable();
            $table->string('master_case_height_cm')->nullable();
            $table->string('master_case_length_cm')->nullable();
            $table->string('master_case_width_cm')->nullable();
            $table->string('master_case_weight_kg')->nullable();
            $table->string('external_packaging_material')->nullable();
            $table->string('each_inner_height_cm')->nullable();
            $table->string('each_inner_length_cm')->nullable();
            $table->string('each_inner_width_cm')->nullable();
            $table->string('each_inner_weight_grams')->nullable();
            $table->string('gtin')->nullable();
            $table->string('mastercasegtin')->nullable();
            
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
        Schema::dropIfExists('alberta_provincial_catalogs');
    }
};
