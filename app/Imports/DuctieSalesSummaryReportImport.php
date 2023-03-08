<?php

namespace App\Imports;

use App\Models\DuctieDiagnosticReport;
use App\Models\DuctieSalesSummaryReport;
use App\Models\CovaSalesSummaryReportRetailer;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DuctieSalesSummaryReportImport implements ToModel, WithHeadingRow
{
    private $uid;
    public function __construct($uid)
    {
        $this->uid = $uid;
    }

    public function model(array $row)
    {
        $covaDaignosticReport = DuctieDiagnosticReport::where('product', $row['productname'])->where('variable', $this->uid)->first();
        if ($covaDaignosticReport) {
            DuctieSalesSummaryReport::create([

                "locationname"  => $row['location'],
                'solddate' => $row['solddate'],
                "product" => $row['productname'],
                "productcategory"  => $row['category'],
                "mastercategory" => $row['mastercategory'],
                "productid"  => $row['productid'],
                'customertype' => $row['customertype'],
                'quantitysold' => $row['quantitysold'],
                'grosssales' => $row['grosssales'],
                'discount' => $row['discount'],
                'netsales' => $row['netsales'],
                'avgpriceperunit' => $row['avgpriceperunit'],
                'taxapplied' => $row['taxapplied'],
                'dd_report_id' => $covaDaignosticReport->id,
            ]);
            return;
        } else {
            return;
        }
    }
}
