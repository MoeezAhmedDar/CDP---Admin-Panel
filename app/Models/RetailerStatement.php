<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerStatement extends Model
{
    use HasFactory;

    protected $fillable = [
        'lp',
        'product',
        'sku',
        'barcode',
        'quantity',
        'quantity_sold',
        'unit_cost',
        'total_purchase_cost',
        'fee_per',
        'fee_in_dollar',
        'ircc_per',
        'ircc_dollar',
        'total_fee',
        'variable',
        'retailerReportSubmission_id',
        'retailerEmail',
        'carve_out',
        'retailer_name',
        'average_price',
        'opening_inventory_units',
        'closing_inventory_units',
        'lp_id',
        'category',
        'brand'
    ];

    public function reportsubmissions()
    {
        return $this->belongsTo(RetailerReportSubmission::class, 'retailerReportSubmission_id', 'id');
    }

    public function lps()
    {
        return $this->belongsTo(Lp::class, 'lp_id', 'id');
    }
}
