<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovaDiagnosticReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'type',
        'aglc_sku',
        'new_brunswick_sku',
        'ocs_sku',
        'ylc_sku',
        'manitoba_barcode_upc',
        'ontario_barcode_upc',
        'saskatchewan_barcode_upc',
        'link_to_product',
        'opening_inventory_units',
        'quantity_purchased_units',
        'reductions_receiving_error_units',
        'returns_from_customers_units',
        'other_additions_units',
        'quantity_sold_units',
        'quantity_destroyed_units',
        'quantity_lost_theft_units',
        'returns_to_supplier_units',
        'other_reductions_units',
        'closing_inventory_units',
        'variable'
    ];

    public function CovaSalesSummaryReport()
    {
        return $this->hasOne(CovaSalesSummaryReport::class, 'cova_diagnostic_report_id', 'id');
    }

    public function retailers()
    {
        return $this->belongsToMany(Retailer::class, 'cova_daignostic_report_retailers', 'retailer_id', 'cova_daignostic_id');
    }
}
