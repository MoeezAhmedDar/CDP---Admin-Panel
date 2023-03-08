<?php

namespace App\Imports;

use App\Models\CovaDiagnosticReport;
use App\Models\CovaSalesSummaryReport;
use App\Models\CovaSalesSummaryReportRetailer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CovaSalesSummaryReportImport implements ToModel, WithHeadingRow
{
    private $uid;
    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function model(array $row)
    {
        $covaDaignosticReport = CovaDiagnosticReport::where('product_name', $row['product'])->where('variable', $this->uid)->first();
        if ($covaDaignosticReport) {
            CovaSalesSummaryReport::create([
                'product' => $row['product'],
                'sku' => $row['sku'],
                'category' => $row['category'],
                'unit' => $row['unit'],
                'items_sold' => $row['items_sold'],
                'items_ref' => $row['items_ref'],
                'net_qty' => $row['net_qty'],
                'gross_sales' => $row['gross_sales'],
                'sub_total' => $row['subtotal'],
                'total_Cost' => $row['total_cost'],
                'gross_profit' => $row['gross_profit'],
                'gross_margin' => $row['gross_margin'],
                'total_margin' => $row['total_discount'],
                'markdown_percentage' => $row['markdown_percent'],
                'average_retail_price' => $row['average_retail_price'],
                'cova_diagnostic_report_id' => $covaDaignosticReport->id
            ]);
            return;
        } else {
            return;
        }
    }
}
