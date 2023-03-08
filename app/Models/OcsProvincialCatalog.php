<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OcsProvincialCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'sub_category',
        'sub_sub_category',
        'product_name',
        'brand',
        'supplier_name',
        'product_short_description',
        'product_long_description',
        'size',
        'colour',
        'image_url',
        'unit_of_measure',
        'stock_status',
        'unit_price',
        'pack_size',
        'minimum_thc_content',
        'maximum_thc_content',
        'thc_content_per_unit',
        'thc_content_per_volume',
        'minimum_cbd_content',
        'maximum_cbd_content',
        'cbd_content_per_unit',
        'cbd_content_per_volume',
        'dried_flower_cannabis_equivalency',
        'plant_type',
        'terpenes',
        'growingmethod',
        'number_of_items_in_a_retail_pack',
        'gtin',
        'ocs_item_number',
        'ocs_variant_number',
        'physical_dimension_width',
        'physical_dimension_height',
        'physical_dimension_depth',
        'physical_dimension_volume',
        'physical_dimension_weight',
        'eaches_per_inner_pack',
        'eaches_per_master_case',
        'inventory_status',
        'storage_criteria',
        'food_allergens',
        'ingredients',
        'street_name',
        'grow_medium',
        'grow_method',
        'grow_region',
        'drying_method',
        'trimming_method',
        'extraction_process',
        'carrier_oil',
        'heating_element_type',
        'battery_type',
        'rechargeable_battery',
        'removable_battery',
        'replacement_parts_available',
        'temperature_control',
        'temperature_display',
        'compatibility',
        'thc_min',
        'thc_max',
        'cbd_min',
        'cbd_max',
        'net_weight',
        'craft',
        
    ];
}
