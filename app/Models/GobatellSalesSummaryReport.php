<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GobatellSalesSummaryReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'compliance_code',
        'supplier_sku',
        'opening_inventory',
        'opening_inventory_value',
        'purchases_from_suppliers_additions',
        'purchases_from_suppliers_value',
        'returns_from_customers_additions',
        'customer_returns_retail_value',
        'other_additions_additions',
        'other_additions_value',
        'sales_reductions',
        'sold_retail_value',
        'destruction_reductions',
        'destruction_value',
        'theft_reductions',
        'theft_value',
        'returns_to_suppliers_reductions',
        'supplier_return_value',
        'other_reductions_reductions',
        'other_reductions_value',
        'closing_inventory',
        'closing_inventory_value',
        'gb_diagnostic_report_id'
    ];


    public function GobatellDiagnosticReport()
    {
        $this->belongsTo(GobatellDiagnosticReport::class, 'gb_diagnostic_report_id', 'id');
    }
}
