<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BritishColumbiaProvincialCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'product_name',
        'brand_name',
        'bc_indigenous_product',
        'subcategory',
        'class',
        'origin_country',
        'origin_region',
        'origin_subregion',
        'su_qty_in_each_case',
        'su_code',
        'su_code_type',
        'case_code',
        'case_code_type',
        'su_product_net_size',
        'su_product_net_size_uom',
        'su_volume_equivalency',
        'su_volume_equivalency_uom',
        'case_weight',
        'case_weight_uom',
        'strain',
        'species',
        'per_activation_cbd_max',
        'per_activation_cbd_min',
        'per_activation_cbd_uom',
        'per_activation_thc_max',
        'per_activation_thc_min',
        'per_activation_thc_uom',
        'per_discrete_unit_cbd_max',
        'per_discrete_unit_cbd_min',
        'per_discrete_unit_cbd_uom',
        'per_discrete_unit_thc_max',
        'per_discrete_unit_thc_min',
        'per_discrete_unit_thc_uom',
        'per_retail_unit_cbd_max',
        'per_retail_unit_cbd_min',
        'per_retail_unit_cbd_uom',
        'per_retail_unit_thc_max',
        'per_retail_unit_thc_min',
        'per_retail_unit_thc_uom',
        'extraction_process',
        'packaging_material',
        'consumption_method',
        'harvesting_method',
        'growing_method',
        'terpene_1_type',
        'terpene_2_type',
        'terpene_3_type',
        'number_of_consumer_items',
        'consumer_item_size',
        'consumer_item_size_uom',
        'ecomm_short_description',
        'ecomm_long_description',
    ];
}
