<?php

namespace App\Jobs;

use App\Models\CleanSheet;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateLpOfferJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $request;
    private $offer;
    public function __construct($request, $offer)
    {
        $this->request = $request;
        $this->offer = $offer;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            DB::beginTransaction();
            $province_id = '';
            $province_name = '';
            $offer = $this->offer->province;
            $this->getProvince($this->offer, $province_id, $province_name);
            $retailerReportSubmission = RetailerReportSubmission::Date()->first();
            if ($this->request['provincial'] != $this->offer->provincial || $this->request['GTin'] != $this->offer->GTin || $this->request['product_name'] != $this->offer->product_name) {
                $da = RetailerStatement::where([
                    ['retailerReportSubmission_id', '>=', $retailerReportSubmission->id],
                    ['quantity', '>', 0],
                ])->where(function ($query) {
                    $query->where('sku', $this->offer->sku)->orWhere('barcode', $this->offer->GTin)->orWhere('product', $this->offer->product);
                    return $query;
                })->whereHas('reportsubmissions', function ($query) use ($province_id, $province_name) {
                    return $query->where('province', $province_id)->orWhere('province', $province_name);
                })->where('lp_id', $this->offer->lp_id)->delete();

                $this->checkCleanSheet($retailerReportSubmission, $this->request['provincial'], $this->request['GTin'], $this->request['product_name'], $offer);
            }

            if ($this->request['unit_cost'] != $this->offer->unit_cost) {
                $retailerStatements = RetailerStatement::where([
                    ['retailerReportSubmission_id', '>=', $retailerReportSubmission->id],
                    ['sku', $this->request['provincial']]
                ])->whereHas('reportsubmissions', function ($query) use ($province_id, $province_name) {
                    return $query->where('province', $province_id)->orWhere('province', $province_name);
                })->get();

                foreach ($retailerStatements as $retailerStatement) {
                    $retailerStatement->unit_cost = trim($this->request['unit_cost'], '$');
                    $retailerStatement->total_purchase_cost = (int)$retailerStatement->quantity * (float)$retailerStatement->unit_cost;
                    $retailerStatement->fee_in_dollar = (float)$retailerStatement->total_purchase_cost * (float)$retailerStatement->fee_per / 100;
                    $retailerStatement->ircc_dollar = (float)$retailerStatement->fee_in_dollar * (float)$retailerStatement->ircc_per / 100;
                    $retailerStatement->total_fee = (float)$retailerStatement->fee_in_dollar - (float)$retailerStatement->ircc_dollar;

                    $retailerStatement->save();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
        }
    }

    private function checkCleanSheet($retailerReportSubmission, $sku, $barcode, $product_name, $offer)
    {
        $province_id = '';
        $province_name = '';
        $this->getProvince($this->offer, $province_id, $province_name);
        $cleanSheet = CleanSheet::where('retailerReportSubmission_id', '>=', $retailerReportSubmission->id)->where(function ($query) use ($sku, $barcode, $product_name) {
            $query->where('sku', $sku)->orWhere('barcode', $barcode)->orWhere('product_name', $product_name);
            return $query;
        })->whereHas('reportsubmission', function ($query) use ($offer, $province_name, $province_id) {
            return $query->where('province', $province_id)->orWhere('province', $province_name);
        })->get();

        if ($cleanSheet != null) {
            foreach ($cleanSheet as $sheet) {
                $retailerStatment = new RetailerStatement();
                $retailerStatment->lp = $this->offer->lps->user->name;
                $retailerStatment->product = $product_name ? $product_name : $sheet->product_name;
                $retailerStatment->brand = $this->offer->brand ? $this->offer->brand : '';
                $retailerStatment->category = $this->offer->category ? $this->offer->category : $sheet->category;
                $retailerStatment->sku = $sku ? $sku : $sheet->sku;
                $retailerStatment->barcode = $barcode ? $barcode : $sheet->barcode;
                $retailerStatment->quantity = (int)$sheet->purchased ? $sheet->purchased : 0;
                $retailerStatment->unit_cost = $this->request['unit_cost'] ? trim($this->request['unit_cost'], '$') : trim($sheet->average_cost, '$');
                $retailerStatment->total_purchase_cost = $retailerStatment->quantity * $retailerStatment->unit_cost;
                $retailerStatment->fee_per = (float)trim($this->offer->data, '%') * 100;

                $retailerStatment->fee_in_dollar
                    = (float)$retailerStatment->total_purchase_cost * $retailerStatment->fee_per / 100;
                $retailerStatment->ircc_per = '20';
                $retailerStatment->ircc_dollar
                    = $retailerStatment->fee_in_dollar * (int)$retailerStatment->ircc_per / 100;
                $retailerStatment->total_fee = $retailerStatment->fee_in_dollar - $retailerStatment->ircc_dollar;
                $retailerStatment->quantity_sold = $sheet->sold;
                $retailerStatment->average_price = $sheet->average_price;
                $retailerStatment->opening_inventory_units = $sheet->opening_inventory_units;
                $retailerStatment->closing_inventory_units = $sheet->closing_inventory_units;
                $retailerStatment->lp_id = $this->offer->lp_id;
                $retailerStatment->retailerReportSubmission_id = $sheet->retailerReportSubmission_id;
                $retailerStatment->save();
            }
        }
        return;
    }

    private function getProvince($offer, &$province_id, &$province_name)
    {
        if ($offer->province == 'ON' || $offer->province == 'Ontario') {
            $province_name = 'Ontario';
            $province_id = 'ON';
        } elseif ($offer->province == 'MB' || $offer->province == 'Manitoba') {
            $province_name = 'Manitoba';
            $province_id = 'MB';
        } elseif ($offer->province == 'BC' || $offer->province == 'British Columbia') {
            $province_name = 'British Columbia';
            $province_id = 'BC';
        } elseif ($offer->province == 'AB' || $offer->province == 'Alberta') {
            $province_name = 'Alberta';
            $province_id = 'AB';
        } elseif ($offer->province == 'SK' || $offer->province == 'Saskatchewan') {
            $province_name = 'Saskatchewan';
            $province_id = 'SK';
        }

        return;
    }
}
