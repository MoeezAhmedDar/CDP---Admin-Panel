<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaskatchewanProvincialCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'sku',
        'producer_type',
        'origin',
        'brand',
        'product_name',
        'gtin',
        'strain',
        'size_grams',
        'thc',
        'cbd',
        'terp',
        'pack_date',
        'per_unit_cost',
        'qtycase',
        'case_price',
        'cases_available',
        'quantity_of_cases_to_order',
        'total',
        'invest_per_gram',
    ];
}
