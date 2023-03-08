<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpStatement extends Model
{
    use HasFactory;

    protected $fillale = [
        'provice',
        'retailer',
        'category',
        'product',
        'sku',
        'opening_inventory_units',
        'closing_inventory_units',
        'total_sales_quantity',
        'quantity_purchased',
        'unit_cost',
        'total_purchased_cost',
        'total_fee_percentage',
        'total_fee_dollars',
        'variable',
        'sold',
        'average_price',
        'retailer_dba'
    ];
}
