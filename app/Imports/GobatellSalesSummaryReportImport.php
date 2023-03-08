<?php

namespace App\Imports;

use App\Models\GobatellDiagnosticReport;
use App\Models\GobatellSalesSummaryReport;
use App\Models\GobatellSalesSummaryReportRetailer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GobatellSalesSummaryReportImport  implements ToModel, WithHeadingRow
{
    private $uid;
    public function __construct($uid)
    {
        $this->uid = $uid;
    }
    public function model(array $row)
    {
        $gobatellDaignosticReport = GobatellDiagnosticReport::where('supplier_sku',   $row['supplier_sku'])->where('variable', $this->uid)->first();

        if ($gobatellDaignosticReport) {
            GobatellSalesSummaryReport::create([
                'compliance_code' => $row['compliance_code'],
                'supplier_sku' => $row['supplier_sku'],
                'opening_inventory' => $row['opening_inventory'],
                'opening_inventory_value' => $row['opening_inventory_value'],
                'purchases_from_suppliers_additions' => $row['purchases_from_suppliers_additions'],
                'purchases_from_suppliers_value' => $row['purchases_from_suppliers_value'],
                'returns_from_customers_additions' => $row['returns_from_customers_additions'],
                'customer_returns_retail_value' => $row['customer_returns_retail_value'],
                'other_additions_additions' => $row['other_additions_additions'],
                'other_additions_value' => $row['other_additions_value'],
                'sales_reductions' => $row['sales_reductions'],
                'sold_retail_value' => $row['sold_retail_value'],
                'destruction_reductions' => $row['destruction_reductions'],
                'destruction_value' => $row['destruction_value'],
                'theft_reductions' => $row['theft_reductions'],
                'theft_value' => $row['theft_value'],
                'returns_to_suppliers_reductions' => $row['returns_to_suppliers_reductions'],
                'supplier_return_value' => $row['supplier_return_value'],
                'other_reductions_reductions' => $row['other_reductions_reductions'],
                'other_reductions_value' => $row['other_reductions_value'],
                'closing_inventory' => $row['closing_inventory'],
                'closing_inventory_value' => $row['closing_inventory_value'],
                'supplier_return_value' => $row['supplier_return_value'],
                'gb_diagnostic_report_id' => $gobatellDaignosticReport->id

            ]);
            return;
        } else {
            return;
        }
    }
}
