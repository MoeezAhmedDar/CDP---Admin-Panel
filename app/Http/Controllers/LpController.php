<?php

namespace App\Http\Controllers;

use App\Exports\date;
use App\Exports\LpStatementExport;
use App\Http\Requests\Lp\LpAddRequest;
use App\Http\Requests\Lp\LpStoreRequest;
use App\Http\Requests\Lp\LpUpdateRequest;
use App\Http\Requests\Lp\LpVariableFeeStoreRequest;
use App\Http\Requests\Lp\UploadIndividualCsvRequest;
use App\Http\Requests\Lp\LpUpdateOfferRequest;
use App\Models\Lp;
use Illuminate\Http\Request;
use App\Models\LpVariableFeeStructure;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\User\LpRepositoryInterface;
use App\Models\CarveOut;
use App\Models\LpStatement;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use App\Traits\CanadaCities;
use App\Traits\GlobalVariables;
use App\Traits\ReturnMessage;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LpController extends Controller
{
    use ReturnMessage, GlobalVariables;
    private LpRepositoryInterface $lpRepository;

    public function __construct(LpRepositoryInterface $lpRepository)
    {
        $this->lpRepository = $lpRepository;
    }
    public function add(LpAddRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->lpRepository->add($request);

            $messages['success'] = "Lp Added Successfully";
            DB::commit();
            return redirect()
                ->route('lps.index')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorMessage($e->getMessage());
        }
    }
    public function index(Request $request, $status = null)
    {
        if (!Auth::user()->hasPermissionTo('lp-list')) {
            return redirect()->route('dashboard');
        }

        return view(
            'admin.lp.index',
            ['lps' => $this->lpRepository->index($request, $status)]
        );
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('lp-create')) {
            return redirect()->route('dashboard');
        }

        return view(
            'admin.lp.create',
            ['provinces' => $this->getCanadaCities()]
        );
    }

    public function store(LpStoreRequest $request, Lp $lp)
    {
        try {
            DB::transaction(function () use ($request, &$lp) {
                $this->lpRepository->store($request, $lp);

                $messages['succes'] = "Lp Added Successfully";
                return redirect()
                    ->route('lps.fee.structure', [$lp->id])
                    ->with('messages', $messages);
            });
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage());
        }
    }

    public function show(Lp $lp)
    {
        if (!Auth::user()->hasPermissionTo('lp-show')) {
            return redirect()->route('dashboard');
        }

        return view(
            'admin.lp.show',
            ['lp' => $this->lpRepository->show($lp)]
        );
    }

    public function offers(Lp $lp)
    {
        return view(
            'admin.lp.offers.index',
            ['offers' => $this->lpRepository->offers($lp)]
        );
    }

    public function edit(Lp $lp)
    {
        if (!Auth::user()->hasPermissionTo('lp-edit')) {
            return redirect()->route('dashboard');
        }
        $lpData = $this->lpRepository->edit($lp);

        return view(
            'admin.lp.edit',
            [
                'lp' => $lpData['lp'],
                'provinces' => $this->getCanadaCities(),
                'statuses' => $lpData['statuses']
            ]
        );
    }

    public function update_offer(LpUpdateOfferRequest $request, LpVariableFeeStructure $offer)
    {
        $this->lpRepository->update_offer($request, $offer);

        $messages['success'] = "LP Offer Updated Successfully";
        return redirect()
            ->back()
            ->with('messages', $messages);
    }

    public function update(LpUpdateRequest $request, Lp $lp)
    {
        try {
            DB::transaction(function () use ($request, $lp) {
                $this->lpRepository->update($request, $lp);
            });

            $messages['success'] = "Lp Updated Successfully";
            return redirect()
                ->route('lps.index')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage());
        }
    }
    public function destroy($id)
    {
        $lp =  Lp::find($id)->delete();

        return $this->errorMessage("LP Deleted Sucessfully");
    }

    public function destroy_offer($id)
    {
        $lp =  LpVariableFeeStructure::where('id', $id)->firstorfail()->delete();
        return $this->errorMessage("Offer Deleted Sucessfully");
    }

    public function variable_fee_store(LpVariableFeeStoreRequest $request, Lp $lp)
    {
        try {
            DB::beginTransaction();
            $this->lpRepository->variable_fee_store($request, $lp);
            DB::commit();
            return view('admin.lp.success-message');
        } catch (\Exception $e) {

            DB::rollback();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function upload_individual_csv(UploadIndividualCsvRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->lpRepository->upload_individual_csv($request);
            DB::commit();

            $messages['success'] = "LP Offers Added Successfully";
            return redirect()
                ->route('lps.offers', [$request->lp_id])
                ->with('messages', $messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function exportLpStatement(Request $request)
    {
        $retailerStatments = RetailerStatement::whereHas('reportsubmissions', function ($query) use ($request) {
            $query->whereMonth('date', Carbon::parse($request->month)->format('m'));
            $query->whereYear('date', Carbon::parse($request->month)->format('Y'));

            return $query;
        })
            ->where('lp_id', $request->lp_id)
            ->get();

        $uid = date('d-m-y h:i:s') . rand(1, 100000);

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
                $lpStatement = new LpStatement();
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
        return Excel::download(new LpStatementExport($uid), Carbon::parse($request->month)->format('M-Y') . '-Statement' . '.xlsx');
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
