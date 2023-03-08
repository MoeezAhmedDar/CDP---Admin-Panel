<?php

namespace App\Imports;

use App\Models\GreenlineReport;
use App\Models\GreenlineRetailerReport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GreenlineReportImport implements ToModel, WithHeadingRow
{
    private $address, $retailer_id, $pos;
    public function __construct($address, $retailer_id, $pos)
    {
        $this->address = $address;
        $this->retailer_id = $retailer_id;
        $this->pos = $pos;
    }

    public function model(array $row)
    {
        $greenlineReport = GreenlineReport::create([
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
        ]);

        GreenlineRetailerReport::create([
            'retailer_id' => $this->retailer_id,
            'greenline_report_id' => $greenlineReport->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            'location' => $this->address->location,
            'province' => $this->address->province,
            'pos' => $this->pos
        ]);

        return;
    }
}
