<?php

namespace App\Imports;

use App\Models\DuctieDiagnosticReport;
use App\Models\DuctieDiagnosticReportRetailer;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DuctieDiagnosticReportImport implements ToModel, WithHeadingRow
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
        $ductieReport = DuctieDiagnosticReport::create([
            'product' => $row['product'],
            'status' => $row['status'],
            'vendor' => $row['vendor'],
            'datepurchashed' => $row['datepurchashed'],
            'datereceived' => $row['datereceived'],
            'item' => $row['item'],
            'sku' => $row['sku'],
            'upcgtin' => $row['upcgtin'],
            'provincial_sku' => $row['provincial_sku'],
            'quantitypurchased' => $row['quantitypurchased'],
            'quantityreceived' => $row['quantityreceived'],
            'unit_cost' => $row['cost'],
            'variable' => $this->uid,
        ]);

        DuctieDiagnosticReportRetailer::create([
            'retailer_id' => $this->retailer_id,
            'dd_report_id' => $ductieReport->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            'location' => $this->address->location,
            'province' => $this->address->province,
            'pos' => $this->pos
        ]);

        return;
    }
}
