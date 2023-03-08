<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GobatellDiagnosticReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'storelocation',
        'store_sku',
        'product',
        'compliance_code',
        'supplier_sku',
        'pos_equivalent_grams',
        'compliance_weight',
        'opening_inventory',
        'purchases_from_suppliers_additions',
        'returns_from_customers_additions',
        'other_additions_additions',
        'sales_reductions',
        'destruction_reductions',
        'theft_reductions',
        'returns_to_suppliers_reductions',
        'other_reductions_reductions',
        'closing_inventory',
        'product_url',
        'inventory_transactions_url',
        'variable'
    ];

    public function GobatellSalesSummaryReport()
    {
        return $this->hasOne(GobatellSalesSummaryReport::class, 'gb_diagnostic_report_id', 'id');
    }

    public function retailers()
    {
        return $this->belongsToMany(Retailer::class, 'gb_daignostic_report_retailers', 'retailer_id', 'gb_daignostic_id');
    }
}
