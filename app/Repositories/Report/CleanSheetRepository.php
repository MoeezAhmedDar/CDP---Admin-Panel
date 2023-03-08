<?php

namespace App\Repositories\Report;

use App\Interfaces\Report\CleanSheetRepositoryInterface;
use App\Jobs\UpdateDirtyRow;
use App\Models\AlbertaProvincialCatalog;
use App\Models\BritishColumbiaProvincialCatalog;
use App\Models\CleanSheet;
use App\Models\MbllProvincialCatalog;
use App\Models\OcsProvincialCatalog;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use App\Models\SaskatchewanProvincialCatalog;
use Exception;
use Illuminate\Support\Facades\DB;

class CleanSheetRepository implements CleanSheetRepositoryInterface
{
    public function dirty_rows($id, $retailer_id, $request)
    {
        $retailerReportSubmission = RetailerReportSubmission::where('id', $id)->first();

        $dirtyRows = $request->has('dirty_row') ?
            CleanSheet::where([
                ['retailerReportSubmission_id', $id],
                ['flag', '1'],
            ])->where(function ($q) use ($request) {
                $q->orWhere('product_name', 'LIKE', "%{$request->dirty_row}%");
                $q->orWhere('sku', 'LIKE', "%{$request->dirty_row}%");
                $q->orWhere('barcode', 'LIKE', "%{$request->dirty_row}%");
            })
                ->paginate(10)
            :
            CleanSheet::where([
                ['retailerReportSubmission_id', $id],
                ['flag', '1'],
            ])->paginate(10);

        return $dirtyRows;
    }

    public function update($request, $id)
    {
        try
        {
            DB::beginTransaction();
            $cl_sheet = CleanSheet::find($id);
    
            if ($request->province == 'BC' || $request->province == 'British Columbia') {
                $provincialCatalog = BritishColumbiaProvincialCatalog::where('sku', $request->sku)
                    ->orWhere('product_name', $request->product_name)
                    ->orWhere('su_code', $request->barcode)->get();
            } elseif ($request->province == 'SK' || $request->province == 'Saskatchewan') {
                $provincialCatalog = SaskatchewanProvincialCatalog::where('sku', $request->sku)
                    ->orWhere('product_name', $request->product_name)
                    ->orWhere('gtin', $request->barcode)->get();
            } elseif ($request->province == 'AB' || $request->province == 'Alberta') {
                $provincialCatalog = AlbertaProvincialCatalog::where('aglc_sku', $request->sku)
                    ->orWhere('product_name', $request->product_name)
                    ->orWhere('gtin', $request->barcode)->get();
            } elseif ($request->province == 'MB' || $request->province == 'Manitoba') {
                $provincialCatalog = MbllProvincialCatalog::where('skumbll_item_number', $request->sku)
                    ->orWhere('description1', $request->product_name)
                    ->orWhere('upcgtin', $request->barcode)->get();
            } elseif ($request->province == 'ON' || $request->province == 'Ontario') {
                $provincialCatalog = OcsProvincialCatalog::where('ocs_variant_number', $request->sku)
                    ->orWhere('product_name', $request->product_name)
                    ->orWhere('gtin', $request->barcode)->get();
            }
    
            if (count($provincialCatalog) == '0') {
                $messages['warning'] = "Didn't Match in Provincial Catalouge";
                return $messages;
            }
            $collection = $request->except(['_method', '_token']);
            $collection['flag'] = '0';
            $collection['id'] = $id;
            $collection['comments'] = ' ';
            $cleanSheet = CleanSheet::updateOrCreate(['id' => $collection['id']], $collection);
            $cleanSheet->save();
            $messages['success'] = "Record Updated Sucessfully";

            if ($cl_sheet) {
                $report_submission = RetailerReportSubmission::find($cl_sheet['retailerReportSubmission_id']);
                dispatch(new UpdateDirtyRow($report_submission->id,$report_submission->retailer->id));
            }

            DB::commit();
            return $messages;
        }
        catch(\Exception $e)
        {
            DB::rollback();
            dd("error");
        }
    }
}
