<?php

namespace App\Imports;

use App\Models\GobatellDiagnosticReport;
use App\Models\GobatellSalesSummaryReport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\GobatellDiagnosticReportRetailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GobatellDiagnosticReportImport implements ToModel, WithHeadingRow
{
    private $address, $retailer_id, $uid, $pos;
    public function __construct($address, $retailer_id, $uid, $pos)
    {
        $this->address = $address;
        $this->retailer_id = $retailer_id;
        $this->uid = $uid;
        $this->pos = $pos;
    }

    public function model(array $row)
    {
        $gobatellDiagnosticReport = GobatellDiagnosticReport::create([
            'storelocation' => $row['storelocation'],
            'store_sku'  => $row['store_sku'],
            'product'  => $row['product'],
            'compliance_code'  => $row['compliance_code'],
            'supplier_sku'  => $row['supplier_sku'],
            'pos_equivalent_grams'  => $row['pos_equivalent_grams'],
            'compliance_weight' => $row['compliance_weight'],
            'opening_inventory'  => $row['opening_inventory'],
            'purchases_from_suppliers_additions' => $row['purchases_from_suppliers_additions'],
            'returns_from_customers_additions' => $row['returns_from_customers_additions'],
            'other_additions_additions'  => $row['other_additions_additions'],
            'sales_reductions' => $row['sales_reductions'],
            'destruction_reductions' => $row['destruction_reductions'],
            'theft_reductions'  => $row['theft_reductions'],
            'returns_to_suppliers_reductions'  => $row['returns_to_suppliers_reductions'],
            'other_reductions_reductions'  => $row['other_reductions_reductions'],
            'closing_inventory' => $row['closing_inventory'],
            'product_url' => $row['product_url'],
            'inventory_transactions_url'  => $row['inventory_transactions_url'],
            'variable' => $this->uid,
        ]);

        GobatellDiagnosticReportRetailer::create([
            'retailer_id' => $this->retailer_id,
            'gb_diagnostic_id' => $gobatellDiagnosticReport->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            'location' => $this->address->location,
            'province' => $this->address->province,
            'pos' => $this->pos
        ]);

        return;
    }
}
