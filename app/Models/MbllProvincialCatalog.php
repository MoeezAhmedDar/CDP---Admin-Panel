<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MbllProvincialCatalog extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_qty_cases',
        'list_type',
        'skumbll_item_number',
        'upcgtin',
        'supplier',
        'brand',
        'type',
        'sub_type',
        'description1',
        'thc_range',
        'cbd_range',
        'unit_volsize',
        'unit_of_measure',
        'unit_price',
        'units_per_case',
        'case_price',
        'product_notifications',
    ];
}
