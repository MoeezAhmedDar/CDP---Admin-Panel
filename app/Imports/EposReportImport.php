<?php

namespace App\Imports;

use App\Models\eposReports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;

class EposReportImport implements ToModel, WithHeadingRow
{
    private $retailerReportSubmission;
    public function __construct($retailerReportSubmission)
    {
        $this->retailerReportSubmission = $retailerReportSubmission;
    }

    public function model(array $row)
    {
        $eposReports = eposReports::create([
            'sku' => $row['sku'],
            'name' => $row['name'],
            'barcode' => $row['barcode'],
            'brand' => $row['brand'],
            'compliance_category' => $row['compliance_category'],
            'opening' => $row['opening'],
            'sold' => $row['sold'],
            'purchased' => $row['purchased'],
            'closing' => $row['closing'],
            'average_price' => $row['average_price'],
            'average_cost' => $row['average_cost'],
            'retailerReportSubmission_id' => $this->retailerReportSubmission->id,
        ]);

        return;
    }
}
