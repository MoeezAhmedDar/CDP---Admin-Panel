<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CleanSheet extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'product_name',
        'category',
        'brand',
        'sold',
        'purchased',
        'average_price',
        'average_cost',
        'barcode',
        'variable',
        'retailerReportSubmission_id',
        'flag',
        'comments',
        'opening_inventory_units',
        'closing_inventory_units'
    ];

    public function reportsubmission()
    {
        return $this->belongsTo(RetailerReportSubmission::class, 'retailerReportSubmission_id', 'id');
    }
}
