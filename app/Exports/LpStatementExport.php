<?php

namespace App\Exports;

use App\Models\LpStatement;
use App\Models\RetailerStatement;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LpStatementExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    private $uid;

    public function __construct($uid)
    {
        $this->uid = $uid;
    }
    public function collection()
    {
        return LpStatement::where('variable', $this->uid)->select(
            'provice',
            'retailer_dba',
            'retailer',
            'product',
            'sku',
            'category',
            'brand',
            'quantity_purchased',
            'sold',
            'average_price',
            'opening_inventory_units',
            'closing_inventory_units',
            'unit_cost',
            'total_purchased_cost',
            'total_fee_percentage',
            'total_fee_dollars'
        )->get();
    }

    public function headings(): array
    {
        return [
            "Province", "Retailer DBA", "Retailer", 'Product', "SKU", "Category", "Brand", "Quantity Purchased", "Quantity Sold", "Average Price", "Opening Inventory Units", "Closing Inventory Units", "Unit Cost", "Total Purchase Cost", "Total Fee(%)", "Total Fee($)"
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
