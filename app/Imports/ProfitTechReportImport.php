<?php

namespace App\Imports;

use App\Models\ProfitTechReport;
use App\Models\ProfitTechRetailerReport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ProfitTechReportImport implements ToModel, WithHeadingRow
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
        $profittechReport = ProfitTechReport::create([
            'product_sku' => $row['product_sku'],
            'opening_inventory_units' => $row['opening_inventory_units'],
            'opening_inventory_value' => $row['opening_inventory_value'],
            'quantity_purchased_units' => $row['quantity_purchased_units'],
            'quantity_purchased_value' => $row['quantity_purchased_value'],
            'quantity_purchased_units_transfer' => $row['quantity_purchased_units_transfer'],
            'quantity_purchased_value_transfer' => $row['quantity_purchased_value_transfer'],
            'returns_from_customers_units' => $row['returns_from_customers_units'],
            'returns_from_customers_value' => $row['returns_from_customers_value'],
            'other_additions_units' => $row['other_additions_units'],
            'other_additions_value' => $row['other_additions_value'],
            'quantity_sold_instore_units' => $row['quantity_sold_instore_units'],
            'quantity_sold_instore_value' => $row['quantity_sold_instore_value'],
            'quantity_sold_online_units' => $row['quantity_sold_online_units'],
            'quantity_sold_online_value' => $row['quantity_sold_online_value'],
            'quantity_sold_units_transfer' => $row['quantity_sold_units_transfer'],
            'quantity_sold_value_transfer' => $row['quantity_sold_value_transfer'],
            'quantity_destroyed_units' => $row['quantity_destroyed_units'],
            'quantity_destroyed_value' => $row['quantity_destroyed_value'],
            'quantity_losttheft_units' => $row['quantity_losttheft_units'],
            'quantity_losttheft_value' => $row['quantity_losttheft_value'],
            'returns_to_aglc_units' => $row['returns_to_aglc_units'],
            'returns_to_aglc_value' => $row['returns_to_aglc_value'],
            'other_reductions_units' => $row['other_reductions_units'],
            'other_reductions_value' => $row['other_reductions_value'],
            'closing_inventory_units' => $row['closing_inventory_units'],
            'closing_inventory_value' => $row['closing_inventory_value'],
        ]);

        ProfitTechRetailerReport::create([
            'retailer_id' => $this->retailer_id,
            'profit_tech_report_id' => $profittechReport->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            'location' => $this->address->location,
            'province' => $this->address->province,
            'pos' => $this->pos
        ]);


        return;
    }
}
