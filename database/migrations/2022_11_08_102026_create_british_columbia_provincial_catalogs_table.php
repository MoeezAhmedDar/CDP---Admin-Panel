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
        Schema::create('british_columbia_provincial_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->nullable();
            $table->string('product_name')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('bc_indigenous_product')->nullable();
            $table->string('subcategory')->nullable();
            $table->string('class')->nullable();
            $table->string('origin_country')->nullable();
            $table->string('origin_region')->nullable();
            $table->string('origin_subregion')->nullable();
            $table->string('su_qty_in_each_case')->nullable();
            $table->string('su_code')->nullable();
            $table->string('su_code_type')->nullable();
            $table->string('case_code')->nullable();
            $table->string('case_code_type')->nullable();
            $table->string('su_product_net_size')->nullable();
            $table->string('su_product_net_size_uom')->nullable();
            $table->string('su_volume_equivalency')->nullable();
            $table->string('su_volume_equivalency_uom')->nullable();
            $table->string('case_weight')->nullable();
            $table->string('case_weight_uom')->nullable();
            $table->string('strain')->nullable();
            $table->string('species')->nullable();
            $table->string('per_activation_cbd_max')->nullable();
            $table->string('per_activation_cbd_min')->nullable();
            $table->string('per_activation_cbd_uom')->nullable();
            $table->string('per_activation_thc_max')->nullable();
            $table->string('per_activation_thc_min')->nullable();
            $table->string('per_activation_thc_uom')->nullable();
            $table->string('per_discrete_unit_cbd_max')->nullable();
            $table->string('per_discrete_unit_cbd_min')->nullable();
            $table->string('per_discrete_unit_cbd_uom')->nullable();
            $table->string('per_discrete_unit_thc_max')->nullable();
            $table->string('per_discrete_unit_thc_min')->nullable();
            $table->string('per_discrete_unit_thc_uom')->nullable();
            $table->string('per_retail_unit_cbd_max')->nullable();
            $table->string('per_retail_unit_cbd_min')->nullable();
            $table->string('per_retail_unit_cbd_uom')->nullable();
            $table->string('per_retail_unit_thc_max')->nullable();
            $table->string('per_retail_unit_thc_min')->nullable();
            $table->string('per_retail_unit_thc_uom')->nullable();
            $table->string('extraction_process')->nullable();
            $table->string('packaging_material')->nullable();
            $table->string('consumption_method')->nullable();
            $table->string('harvesting_method')->nullable();
            $table->string('growing_method')->nullable();
            $table->string('terpene_1_type')->nullable();
            $table->string('terpene_2_type')->nullable();
            $table->string('terpene_3_type')->nullable();
            $table->string('number_of_consumer_items')->nullable();
            $table->string('consumer_item_size')->nullable();
            $table->string('consumer_item_size_uom')->nullable();
            $table->text('ecomm_short_description')->nullable();
            $table->text('ecomm_long_description')->nullable();

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
        Schema::dropIfExists('british_columbia_provincial_catalogs');
    }
};
