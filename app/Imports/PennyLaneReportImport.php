<?php

namespace App\Imports;

use App\Models\PennyLaneReport;
use App\Models\PennyLaneRetailerReport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class PennyLaneReportImport implements ToModel, WithHeadingRow
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
        $pennylaneReport = PennyLaneReport::create([
            'store' => $row['store'],
            'product_sku' => $row['product_sku'],
            'description' => $row['description'],
            'uom' => $row['uom'],
            'category' => $row['category'],
            'opening_inventory_units' => $row['opening_inventory_units'],
            'opening_inventory_value' => $row['opening_inventory_value'],
            'quantity_purchased_units' => $row['quantity_purchased_units'],
            'quantity_purchased_value' => $row['quantity_purchased_value'],
            'returns_from_customers_units' => $row['returns_from_customers_units'],
            'returns_from_customers_value' => $row['returns_from_customers_value'],
            'other_additions_units' => $row['other_additions_units'],
            'other_additions_value' => $row['other_additions_value'],
            'quantity_sold_units' => $row['quantity_sold_units'],
            'quantity_sold_value' => $row['quantity_sold_value'],
            'transfer_units' => $row['transfer_units'],
            'transfer_value' => $row['transfer_value'],
            'returns_to_vendor_units' => $row['returns_to_vendor_units'],
            'returns_to_vendor_value' => $row['returns_to_vendor_value'],
            'inventory_adjustment_units' => $row['inventory_adjustment_units'],
            'inventory_adjustment_value' => $row['inventory_adjustment_value'],
            'destroyed_units' => $row['destroyed_units'],
            'destroyed_value' => $row['destroyed_value'],
            'closing_inventory_units' => $row['closing_inventory_units'],
            'closing_inventory_value' => $row['closing_inventory_value'],
            'min_stock' => $row['min_stock'],
            'low_inv' => $row['low_inv'],
        ]);

        PennyLaneRetailerReport::create([
            'retailer_id' => $this->retailer_id,
            'penny_lane_report_id' => $pennylaneReport->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            'location' => $this->address->location,
            'province' => $this->address->province,
            'pos' => $this->pos
        ]);


        return;
    }
}
