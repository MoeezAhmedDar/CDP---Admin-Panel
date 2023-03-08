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
        Schema::create('ocs_provincial_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('category')->nullable();
            $table->string('sub_category')->nullable();
            $table->string('sub_sub_category')->nullable();
            $table->string('product_name')->nullable();
            $table->string('brand')->nullable();
            $table->string('supplier_name')->nullable();
            $table->text('product_short_description')->nullable();
            $table->text('product_long_description')->nullable();
            $table->text('size')->nullable();
            $table->string('colour')->nullable();
            $table->string('image_url')->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->string('stock_status')->nullable();
            $table->string('unit_price')->nullable();
            $table->string('pack_size')->nullable();
            $table->string('minimum_thc_content')->nullable();
            $table->string('maximum_thc_content')->nullable();
            $table->string('thc_content_per_unit')->nullable();
            $table->string('thc_content_per_volume')->nullable();
            $table->string('minimum_cbd_content')->nullable();
            $table->string('maximum_cbd_content')->nullable();
            $table->string('cbd_content_per_unit')->nullable();
            $table->string('cbd_content_per_volume')->nullable();
            $table->string('dried_flower_cannabis_equivalency')->nullable();
            $table->string('plant_type')->nullable();
            $table->string('terpenes')->nullable();
            $table->string('growingmethod')->nullable();
            $table->string('number_of_items_in_a_retail_pack')->nullable();
            $table->string('gtin')->nullable();
            $table->string('ocs_item_number')->nullable();
            $table->string('ocs_variant_number')->nullable();
            $table->string('physical_dimension_width')->nullable();
            $table->string('physical_dimension_height')->nullable();
            $table->string('physical_dimension_depth')->nullable();
            $table->string('physical_dimension_volume')->nullable();
            $table->string('physical_dimension_weight')->nullable();
            $table->string('eaches_per_inner_pack')->nullable();
            $table->string('eaches_per_master_case')->nullable();
            $table->string('inventory_status')->nullable();
            $table->string('storage_criteria')->nullable();
            $table->string('food_allergens')->nullable();
            $table->text('ingredients')->nullable();
            $table->string('street_name')->nullable();
            $table->string('grow_medium')->nullable();
            $table->string('grow_method')->nullable();
            $table->string('grow_region')->nullable();
            $table->string('drying_method')->nullable();
            $table->string('trimming_method')->nullable();
            $table->string('extraction_process')->nullable();
            $table->string('carrier_oil')->nullable();
            $table->string('heating_element_type')->nullable();
            $table->string('battery_type')->nullable();
            $table->string('rechargeable_battery')->nullable();
            $table->string('removable_battery')->nullable();
            $table->string('replacement_parts_available')->nullable();
            $table->string('temperature_control')->nullable();
            $table->string('temperature_display')->nullable();
            $table->string('compatibility')->nullable();
            $table->string('thc_min')->nullable();
            $table->string('thc_max')->nullable();
            $table->string('cbd_min')->nullable();
            $table->string('cbd_max')->nullable();
            $table->string('net_weight')->nullable();
            $table->string('craft')->nullable();

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
        Schema::dropIfExists('ocs_provincial_catalogs');
    }
};
