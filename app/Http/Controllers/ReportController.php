<?php

namespace App\Http\Controllers;

use App\Exports\CleanSheetsExport;
use App\Exports\LpStatementExport;
use Maatwebsite\Excel\Facades\Excel;
use Exception;
use App\Exports\RetailerStatementExport;
use App\Http\Requests\Report\StoreReportSubmissionDate;
use App\Http\Requests\Report\StoreRequest;
use App\Http\Requests\Report\UpdateReportSubmissionDate;
use App\Models\RetailerReportSubmission;
use Carbon\Carbon;
use App\Models\Retailer;
use App\Models\CleanSheet;
use App\Models\Lp;
use App\Models\RetailerAddress;
use App\Models\RetailerStatement;
use Illuminate\Support\Facades\DB;
use App\Interfaces\Report\CleanReportRepositoryInterface;
use App\Interfaces\Report\LpStatementRepositoryInterface;
use App\Jobs\GenerateStatement;
use App\Interfaces\Report\StoreRepositoryInterface;
use App\Models\CanadaCities;
use App\Models\ReportSubmissionDate;
use App\Traits\GlobalVariables;
use Illuminate\Http\Request;
use App\Traits\ReturnMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    use ReturnMessage, GlobalVariables;
    private StoreRepositoryInterface $reportStoreRepository;
    private CleanReportRepositoryInterface $cleanReportRepository;
    private LpStatementRepositoryInterface $lpStatementRepository;

    public function __construct(StoreRepositoryInterface $reportStoreRepository, CleanReportRepositoryInterface $cleanReportRepository, LpStatementRepositoryInterface $lpStatementRepository)
    {
        ini_set('max_execution_time', 30000);
        $this->reportStoreRepository = $reportStoreRepository;
        $this->cleanReportRepository = $cleanReportRepository;
        $this->lpStatementRepository = $lpStatementRepository;
    }

    public function report_create(Retailer $retailer)
    {
        return view('admin.reports.upload-report', compact('retailer'));
    }

    public function report_store(StoreRequest $request, Retailer $retailer)
    {
        $address = RetailerAddress::where('id', $request->location)->first();
        if ($address) {
            try {
                DB::beginTransaction();
                $status = $this->reportStoreRepository->checkRetailerReportSubmission($retailer, $address);
                if ($status) {
                    return $this->errorMessage("You have already submited the Report for this location");
                } else {
                    $retailerReportSubmission = $this->reportStoreRepository->store($retailer, $address, $request);
                    DB::commit();
                    dispatch(new GenerateStatement($retailerReportSubmission->id, $retailer->id, $this->cleanReportRepository));
                    $messages['success'] = "Report Added Successfully";
                    return redirect()
                        ->route('reports.monthly.status')
                        ->with('messages', $messages);
                }
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorMessage($e->getMessage());
            }
        } else {
            return $this->errorMessage("Pos and Location does not match.");
        }
    }

    public function retailer_statement($id, $retailer_id)
    {
        try {
            DB::beginTransaction();
            $checkCleanSheet = CleanSheet::where('retailerReportSubmission_id', $id)->first();
            $retailer = Retailer::where('id', $retailer_id)->first();
            $retailerReportSubmission = RetailerReportSubmission::where('id', $id)->first();

            if (!$checkCleanSheet) {
                $this->cleanReportRepository->checkPos($retailerReportSubmission, $retailer_id);
                $this->cleanReportRepository->checkRetailerStatement($retailerReportSubmission, $retailer_id);
                $retailerReportSubmission_id = $retailerReportSubmission->id;
            } else {
                $retailerSta = RetailerStatement::where('retailerReportSubmission_id', $id)->first();

                if (!$retailerSta) {
                    $this->cleanReportRepository->checkRetailerStatement($retailerReportSubmission, $retailer_id);
                } else {
                    RetailerStatement::where('retailerReportSubmission_id', $id)->delete();
                    $this->cleanReportRepository->checkRetailerStatement($retailerReportSubmission, $retailer_id);
                }
                $retailerReportSubmission_id = $retailerReportSubmission->id;
            }
            DB::commit();

            return Excel::download(new RetailerStatementExport($retailerReportSubmission_id), preg_replace('/[^A-Za-z0-9\-]/', '', $retailer->user->name) . ' ' . $retailerReportSubmission->location . ' ' . Carbon::parse($retailerReportSubmission->date)->format('M-Y') . '.xlsx');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function lp_statement(Lp $lp)
    {
        try {
            DB::beginTransaction();
            $uid = date('d-m-y h:i:s') . rand(1, 100000);
            $this->lpStatementRepository->storeLpStatement($lp, $uid);
            DB::commit();

            return Excel::download(new LpStatementExport($uid), $lp->user->name . Carbon::now()->format('D-M-Y h:i:s') . '.xlsx');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function clean_report($id, $retailer_id)
    {
        try {
            DB::beginTransaction();
            $retailerReportSubmission = RetailerReportSubmission::where('id', $id)->first();
            $checkCleanSheet = CleanSheet::where('retailerReportSubmission_id', $id)->first();
            $retailer = Retailer::where('id', $retailer_id)->first();
            if (!$checkCleanSheet) {
                $this->cleanReportRepository->checkPos($retailerReportSubmission, $retailer_id);
                $retailerReportSubmission_id = $retailerReportSubmission->id;
            } else {
                $retailerReportSubmission_id = $checkCleanSheet->retailerReportSubmission_id;
            }
            DB::commit();
            $path = preg_replace('/[^A-Za-z0-9\-]/', '', $retailer->user->name) . ' ' . $retailerReportSubmission->location . ' ' . Carbon::parse($retailerReportSubmission->date)->format('M') . '.xlsx';

            return Excel::download(new CleanSheetsExport($retailerReportSubmission_id), preg_replace('/[^A-Za-z0-9\-]/', '', $retailer->user->name) . ' ' . $retailerReportSubmission->address->location . ' ' . Carbon::parse($retailerReportSubmission->date)->format('M') . '.xlsx');
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function monthlyReportsStatus(Request $request, $status = null)
    {
        if (Auth::user()->hasRole('Retailer')) {
            $reports =  $this->reportStoreRepository->showReports($request, $status);

            return view('retailers.reports.index')->with([
                'retailerReports' => $reports['retailerReports'],
                'date' => $reports['date'],
            ]);
        } else {
            $reports =  $this->reportStoreRepository->monthlyReportsStatus($request, $status);
            return view('admin.reports.allReportStatus', compact('reports'));
        }
    }

    public function monthlyReportByProvince()
    {
        if (!Auth::user()->hasRole('Retailer') && !Auth::user()->hasRole('Lp')) {
            $monthlyReport = $this->reportStoreRepository->monthlyReportByProvince();

            return view('admin.comparison.index')->with([
                'retailerReport' => $monthlyReport['retailerReport'],
                'lpReport' => $monthlyReport['lpReport']
            ]);
        }
        abort(404);
    }

    public function showEmptyReports()
    {
        $reports = RetailerReportSubmission::whereMonth('date', Carbon::now()->startOfMonth()->subMonth()->format('m'))
            ->whereYear('date', Carbon::now()->startOfMonth()->subMonth()->format('Y'))
            ->with('retailer.user', 'address')
            ->whereHas('retailer', function ($q) {
                return $q->where('status', 'Approved');
            })
            ->whereDoesntHave('retailerStatements')
            ->orderBy('updated_at', 'DESC')
            ->paginate(10);


        return view('admin.comparison.emptyRetailerList')->with(['reports' => $reports]);
    }
}
