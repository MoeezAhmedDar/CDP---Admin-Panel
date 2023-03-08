<?php

namespace App\Repositories\Report;

use App\Interfaces\Report\LpStatementRepositoryInterface;
use App\Models\CarveOut;
use App\Models\LpStatement;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use Illuminate\Support\Facades\Log;

class LpStatementRepository implements LpStatementRepositoryInterface
{
    public function storeLpStatement($lp, $uid)
    {
        $retailerReportSubmission = RetailerReportSubmission::date()->first();
        $data = RetailerStatement::where('lp_id', $lp->id)->where('retailerReportSubmission_id', '>=', $retailerReportSubmission->id)->chunk(50, function ($retailerStatments) use ($uid, $lp) {
            foreach ($retailerStatments as $retailerStatment) {
                $province_name = '';
                $province_id = '';
                $this->getRetailerProvince($retailerStatment->reportsubmissions, $province_name, $province_id);

                $checkCarveout = CarveOut::where([
                    ['retailer_id', $retailerStatment->reportsubmissions->retailer_id],
                    ['lp', $retailerStatment->lps->user->name]
                ])->where(function ($q) use ($province_id, $province_name) {
                    $q->where('location', $province_id)->orWhere('location', $province_name);
                    return $q;
                })->first();
                $check = LpStatement::where([
                    ['retailer', $retailerStatment->retailer],
                    ['product', $retailerStatment->product],
                    ['sku', $retailerStatment->sku],
                    ['quantity_purchased', (float)$retailerStatment->quantity],
                    ['unit_cost', $retailerStatment->unit_cost],
                    ['sold', $retailerStatment->quantity_sold],
                    ['opening_inventory_units', $retailerStatment->opening_inventory_units],
                    ['closing_inventory_units', $retailerStatment->closing_inventory_units],
                    ['variable', $uid],
                ])->first();

                if (!$check && (int)$retailerStatment->quantity > 0 && !$checkCarveout) {
                    $lpStatement = new LpStatement;
                    $lpStatement->provice = $province_id;
                    $lpStatement->retailer = $retailerStatment->reportsubmissions->retailer->DBA . ' ' . $retailerStatment->reportsubmissions->location;
                    $lpStatement->product = $retailerStatment->product;
                    $lpStatement->category = $retailerStatment->category;
                    $lpStatement->brand = $retailerStatment->brand;
                    $lpStatement->sku = $retailerStatment->sku;
                    $lpStatement->total_sales_quantity = '';
                    $lpStatement->quantity_purchased = $retailerStatment->quantity;
                    $lpStatement->unit_cost = $retailerStatment->unit_cost;
                    $lpStatement->total_purchased_cost = (float)$retailerStatment->quantity * (float) $retailerStatment->unit_cost;
                    $lpStatement->total_fee_percentage = $retailerStatment->fee_per;
                    $lpStatement->total_fee_dollars = (float)$lpStatement->total_fee_percentage * (float)$lpStatement->total_purchased_cost / 100;
                    $lpStatement->sold = $retailerStatment->quantity_sold;
                    $lpStatement->average_price = $retailerStatment->average_price;
                    $lpStatement->opening_inventory_units = $retailerStatment->opening_inventory_units;
                    $lpStatement->closing_inventory_units = $retailerStatment->closing_inventory_units;
                    $lpStatement->retailer_dba = $retailerStatment->reportsubmissions->retailer->DBA;
                    $lpStatement->variable = $uid;
                    $lpStatement->save();
                }
            }
        });
        return;
    }

    private function getRetailerProvince($retailerReportSubmission, &$province_id, &$province_name)
    {
        if ($retailerReportSubmission->province == 'ON' || $retailerReportSubmission->province == 'Ontario') {
            $province_name = 'Ontario';
            $province_id = 'ON';
        } elseif ($retailerReportSubmission->province == 'MB' || $retailerReportSubmission->province == 'Manitoba') {
            $province_name = 'Manitoba';
            $province_id = 'MB';
        } elseif ($retailerReportSubmission->province == 'BC' || $retailerReportSubmission->province == 'British Columbia') {
            $province_name = 'British Columbia';
            $province_id = 'BC';
        } elseif ($retailerReportSubmission->province == 'AB' || $retailerReportSubmission->province == 'Alberta') {
            $province_name = 'Alberta';
            $province_id = 'AB';
        } elseif ($retailerReportSubmission->province == 'SK' || $retailerReportSubmission->province == 'Saskatchewan') {
            $province_name = 'Saskatchewan';
            $province_id = 'SK';
        }

        return;
    }
}
