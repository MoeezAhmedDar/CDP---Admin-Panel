<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuctieDiagnosticReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'location',
        'transfer_from',
        'sku',
        'upcgtin',
        'provincial_sku',
        'product',
        'product_category',
        'package_id',
        'external_package_id',
        'received_on',
        'quantity',
        'unit',
        'unit_cost',
        'total_cost',
        'shipping_costs',
        'discount_amount',
        'total_product_grams',
        'unittax',
        'cultivationunittax',
        'vendor',
        'title',
        'order_id',
        'status',
        'fullname',
        'variable',
        'datepurchashed',
        'datereceived',
        'item',
        'quantitypurchased',
        'quantityreceived',
    ];


    public function DuctieSalesSummaryReport()
    {
        return $this->hasOne(DuctieSalesSummaryReport::class, 'dd_report_id', 'id');
    }

    public function retailers()
    {
        return $this->belongsToMany(Retailer::class, 'ductie_diagnostic_report_retailers', 'retailer_id', 'ductie_daignostic_id');
    }
}
