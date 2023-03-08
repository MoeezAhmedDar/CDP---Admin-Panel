<?php

namespace App\Exports;

use App\Models\CarveOut;
use App\Models\Lp;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RetailerStatementExport implements FromCollection, WithMapping, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    private $retailerReportSubmission_id;
    public function __construct($retailerReportSubmission_id)
    {
        $this->retailerReportSubmission_id = $retailerReportSubmission_id;
    }

    public function collection()
    {
        return RetailerStatement::where('retailerReportSubmission_id', $this->retailerReportSubmission_id)->with('reportsubmissions.retailer')->get();
    }

    public function map($row): array
    {
        return [
            $row->reportsubmissions->retailer->DBA,
            $row->lp,
            $this->get_province($row->reportsubmissions),
            $row->reportsubmissions->retailer->DBA . ' ' . $row->reportsubmissions->location,
            $row->product,
            $row->sku,
            $row->quantity,
            $row->unit_cost,
            $row->opening_inventory_units,
            $row->closing_inventory_units,
            $row->quantity_sold,
            $row->average_price,
            $row->total_purchase_cost,
            $row->fee_per,
            $row->fee_in_dollar,
            $row->ircc_per,
            $row->ircc_dollar,
            $row->total_fee
        ];
    }

    public function headings(): array
    {
        return ["DBA", "LP", "Province", "Retailer", 'Product Name', "SKU", "Quantity Purchased", "Unit Cost", "Opening Inventory Units", "Closing Inventory Units", "Quantity Sold", "Average Price", "Total Purchase Cost", " Fee(%)", "Fee($)", "IRCC(%)", "IRCC($)", "Total Payouts"];
    }

    private function get_province($retailerStatement)
    {
        $province_name = '';
        if ($retailerStatement->province == 'ON' || $retailerStatement->province == 'Ontario') {
            $province_name = 'ON';
        } elseif ($retailerStatement->province == 'MB' || $retailerStatement->province == 'Manitoba') {
            $province_name = 'MB';
        } elseif ($retailerStatement->province == 'BC' || $retailerStatement->province == 'British Columbia') {
            $province_name = 'BC';
        } elseif ($retailerStatement->province == 'AB' || $retailerStatement->province == 'Alberta') {
            $province_name = 'AB';
        } elseif ($retailerStatement->province == 'SK' || $retailerStatement->province == 'Saskatchewan') {
            $province_name = 'SK';
        }
        return $province_name;
    }
}
