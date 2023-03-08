<?php

namespace App\Imports;

use App\Models\IdealDiagnosticReport;
use App\Models\IdealSalesSummaryReport;
// use App\Models\CovaSalesSummaryReportRetailer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IdealSalesSummaryReportImport implements ToModel, WithHeadingRow
{

    private $uid;
    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function model(array $row)
    {
        $idealDaignosticReport = IdealDiagnosticReport::where('sku', $row['sku'])->where('variable', $this->uid)->first();
        if ($idealDaignosticReport) {
            IdealSalesSummaryReport::create([
                'sku' => trim($row['sku']),
                'product_description' => $row['product_description'],
                'quantity_purchased' => $row['quantity_purchased'],
                'purchase_amount' => $row['purchase_amount'],
                'return_quantity' => $row['return_quantity'],
                'amount_return' => $row['amount_return'],
                'ideal_diagnostic_report_id' => $idealDaignosticReport->id
            ]);
            return;
        } else {
            return;
        }
    }

}
