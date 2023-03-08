<?php

namespace App\Imports;

use App\Models\CovaDiagnosticReport;
use App\Models\CovaSalesSummaryReport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\CovaDaignosticReportRetailer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CovaDiagnosticReportImport implements ToModel, WithHeadingRow
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
        $covaDiagnosticReport = CovaDiagnosticReport::create([
            'product_name' => $row['product_name'],
            'type' => $row['type'],
            'aglc_sku' => $row['aglc_sku'],
            'new_brunswick_sku' => $row['new_brunswick_sku'],
            'ocs_sku' => $row['ocs_sku'],
            'ylc_sku' => $row['ylc_sku'],
            'manitoba_barcode_upc' => $row['manitoba_barcodeupc'],
            'ontario_barcode_upc' => $row['ontario_barcodeupc'],
            'saskatchewan_barcode_upc' => $row['saskatchewan_barcodeupc'],
            'link_to_product' => $row['link_to_product'],
            'opening_inventory_units' => $row['opening_inventory_units'],
            'quantity_purchased_units' => $row['quantity_purchased_units'],
            'reductions_receiving_error_units' => $row['reductions_receiving_error_units'],
            'returns_from_customers_units' => $row['returns_from_customers_units'],
            'other_additions_units' => $row['other_additions_units'],
            'quantity_sold_units' => $row['quantity_sold_units'],
            'quantity_destroyed_units' => $row['quantity_destroyed_units'],
            'quantity_lost_theft_units' => $row['quantity_lost_theft_units'],
            'returns_to_supplier_units' => $row['returns_to_supplier_units'],
            'other_reductions_units' => $row['other_reductions_units'],
            'closing_inventory_units' => $row['closing_inventory_units'],
            'variable' => $this->uid,
        ]);

        CovaDaignosticReportRetailer::create([
            'retailer_id' => $this->retailer_id,
            'cova_daignostic_id' => $covaDiagnosticReport->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            'location' =>  $this->address->location,
            'province' => $this->address->province,
            'pos' => $this->pos
        ]);

        return;
    }
}
