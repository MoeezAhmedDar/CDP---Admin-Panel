<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlbertaProvincialCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'aglc_sku',
        'quantity',
        'sku_description',
        'available_cases',
        'format',
        'subcategory',
        'thc_min',
        'thc_max',
        'thc_uom',
        'cbd_min',
        'cbd_max',
        'cbd_uom',
        'eachespercase',
        'new_sku_this_week',
        'on_sale',
        'sell_price_per_case',
        'orginal_price_per_case',
        'sell_price_per_unit',
        'msrp',
        'recycle_fees_per_case',
        'deposit_fee_per_case',
        'company_name',
        'brand_name',
        'product_name',
        'long_product_description',
        'merchandising_strategy',
        'type_sub_2_category',
        'strain_sub_3_category',
        'province_of_origin',
        'region_of_product_optional',
        'extraction_process',
        'dominant_terpene_1',
        'dominant_terpene_1_content',
        'dominant_terpene_2',
        'dominant_terpene_2_content',
        'dominant_terpene_3',
        'dominant_terpene_3_content',
        'other_terpenes_list',
        'net_content',
        'content_uom',
        'piece_qty',
        'piece_size',
        'dce_g',
        'master_case_height_cm',
        'master_case_length_cm',
        'master_case_width_cm',
        'master_case_weight_kg',
        'external_packaging_material',
        'each_inner_height_cm',
        'each_inner_length_cm',
        'each_inner_width_cm',
        'each_inner_weight_grams',
        'gtin',
        'mastercasegtin',
    ];
}
