<?php

namespace App\Exports;

use App\Models\CleanSheet;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CleanSheetsExport implements FromCollection, WithMapping, WithHeadings
{
    private $retailerReportSubmission_id;

    public function __construct($retailerReportSubmission_id)
    {
        $this->retailerReportSubmission_id = $retailerReportSubmission_id;
    }
    public function collection()
    {
        return CleanSheet::where('retailerReportSubmission_id', $this->retailerReportSubmission_id)->get();
    }

    public function map($row): array
    {
        return [
            $row->reportsubmission->retailer->user->name,
            $row->reportsubmission->location,
            $row->reportsubmission->province,
            $row->sku,
            $row->brand,
            $row->product_name,
            $row->category,
            $row->sold,
            $row->purchased,
            $row->average_price,
            $row->average_cost,
            $row->opening_inventory_units,
            $row->closing_inventory_units,
            $row->flag,
            $row->comments,
        ];
    }

    public function headings(): array
    {
        return ["Retailer Name", "Location", "Province", 'SKU', "Brand", "product Name", "Category", "Sold", "Purchased", "Average Price", "Average Cost", "Opening Inventory Units", "Closing Inventory Units", "Flag", "Comments"];
    }
}
