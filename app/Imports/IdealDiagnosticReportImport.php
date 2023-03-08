<?php

namespace App\Imports;

use App\Models\IdealDiagnosticReport;
use App\Models\IdealSalesSummaryReport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
// use App\Models\CovaDaignosticReportRetailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class IdealDiagnosticReportImport implements ToModel, WithHeadingRow
{
    private $uid, $retailer_report_submission_id;
    public function __construct($uid, $retailer_report_submission_id)
    {

        $this->uid = $uid;
        $this->retailer_report_submission_id = $retailer_report_submission_id;
    }

    public function model(array $row)
    {
        $idealDiagnosticReport = IdealDiagnosticReport::create([
            'sku' => trim($row['sku']),
            'description' => $row['description'],
            'opening' => $row['opening'],
            'purchases' => $row['purchases'],
            'returns' => $row['returns'],
            'trans_in' => $row['trans_in'],
            'trans_out' => $row['trans_out'],
            'unit_sold' => $row['unit_sold'],
            'write_offs' => $row['write_offs'],
            'closing' => $row['closing'],
            'net_sales_ex' => $row['net_sales_ex'],
            'retailerReportSubmission_id' => $this->retailer_report_submission_id,
            'variable' => $this->uid,
        ]);

        return;
    }
}
