<?php

namespace App\Repositories\Report;

use App\Imports\CovaDiagnosticReportImport;
use App\Imports\CovaSalesSummaryReportImport;
use App\Imports\IdealDiagnosticReportImport;
use App\Imports\IdealSalesSummaryReportImport;
use App\Imports\DuctieDiagnosticReportImport;
use App\Imports\DuctieSalesSummaryReportImport;
use App\Imports\EposReportImport;
use App\Imports\GobatellDiagnosticReportImport;
use App\Imports\GobatellSalesSummaryReportImport;
use App\Imports\GreenlineReportImport;
use App\Imports\PennyLaneReportImport;
use App\Imports\ProfitTechReportImport;
use App\Imports\TechPosReportImport;
use App\Interfaces\Report\StoreRepositoryInterface;
use App\Models\Activity;
use App\Models\CarveOut;
use App\Models\ReportSubmissionDate;
use App\Models\Retailer;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use App\Traits\ReturnMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\FuncCall;

use function PHPUnit\Framework\isNull;

class StoreRepository implements StoreRepositoryInterface
{
    use ReturnMessage;
    public function checkRetailerReportSubmission($retailer, $address)
    {
        $status = RetailerReportSubmission::where('retailer_id', $retailer->id)->whereMonth('date', Carbon::now()->startOfMonth()->subMonth()->format('m'))->whereYear('date', Carbon::now()->startOfMonth()->subMonth()->format('Y'))->where('address_id', $address->id)->where('status', 'Submited')->first();

        return $status;
    }

    public function store($retailer, $address, $request)
    {
        $uid = date('d-m-y h:i:s') . rand(1, 100000);
        $retailerReportSubmission = RetailerReportSubmission::create([
            'retailer_id' => $retailer->id,
            'status' => 'Pending',
            'province' => $address->province,
            'location' => $address->location,
            'address_id' => $address->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
        ]);

        if ($request->pos == 'cova') {
            $this->storeCova($retailer, $request, $address, $uid);
        } elseif ($request->pos == 'gobatell') {
            $this->storeGlobalTill($retailer, $request, $address, $uid);
        } elseif ($request->pos == 'greenline') {
            $this->storeGreenline($retailer, $request, $address);
        } elseif ($request->pos == 'epos') {
            $this->storeEpos($retailer, $request, $address, $retailerReportSubmission);
        } elseif ($request->pos == 'techpos') {
            $this->storeTechpos($retailer, $request, $address);
        } elseif ($request->pos == 'pennylane') {
            $this->storePennylane($retailer, $request, $address);
        } elseif ($request->pos == 'ductie') {
            $this->storeDuctie($retailer, $request, $address, $uid);
        } elseif ($request->pos == 'profittech') {
            $this->storeProfitech($retailer, $request, $address);
        } elseif ($request->pos == 'ideal') {
            $retailerReportSubmission_id =  $retailerReportSubmission->id;
            $this->storeIdeal($retailer, $request, $address, $uid, $retailerReportSubmission_id);
        }

        return $retailerReportSubmission;
    }

    private function storeCova($retailer, $request, $address, $uid)
    {
        $request->validate([
            'sales_summary_report' => 'required|file|mimes:csv,xlsx',
            'diagnostic_report' => 'required|file|mimes:csv,xlsx',
        ]);

        Excel::import(new CovaDiagnosticReportImport($address, $retailer->id, $uid, $request->pos), request()->file('diagnostic_report'));
        Excel::import(new CovaSalesSummaryReportImport($uid), request()->file('sales_summary_report'));

        $file1 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('diagnostic_report')->getClientOriginalName(), '.' . $request->file('diagnostic_report')->getClientOriginalExtension()) . time() . '.' . $request->diagnostic_report->extension();
        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('sales_summary_report')->getClientOriginalName(), '.' . $request->file('sales_summary_report')->getClientOriginalExtension()) . time() . '.' . $request->sales_summary_report->extension();
        $path1 = $request->diagnostic_report->move(public_path('reports/'), $file1);
        $path2 = $request->sales_summary_report->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file1, $file2, $address);
        return;
    }

    private function storeIdeal($retailer, $request, $address, $uid, $retailerReportSubmission_id)
    {
        $request->validate([
            'sales_summary_report' => 'required|file|mimes:csv,xlsx',
            'diagnostic_report' => 'required|file|mimes:csv,xlsx',
        ]);
        Excel::import(new IdealDiagnosticReportImport($uid, $retailerReportSubmission_id), request()->file('diagnostic_report'));
        Excel::import(new IdealSalesSummaryReportImport($uid), request()->file('sales_summary_report'));

        $file1 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('diagnostic_report')->getClientOriginalName(), '.' . $request->file('diagnostic_report')->getClientOriginalExtension()) . time() . '.' . $request->diagnostic_report->extension();
        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('sales_summary_report')->getClientOriginalName(), '.' . $request->file('sales_summary_report')->getClientOriginalExtension()) . time() . '.' . $request->sales_summary_report->extension();
        $path1 = $request->diagnostic_report->move(public_path('reports/'), $file1);
        $path2 = $request->sales_summary_report->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file1, $file2, $address);
        return;
    }

    private function storeGlobalTill($retailer, $request, $address, $uid)
    {
        $request->validate([
            'sales_summary_report' => 'required|file|mimes:csv,xlsx',
            'diagnostic_report' => 'required|file|mimes:csv,xlsx',
        ]);
        Excel::import(new GobatellDiagnosticReportImport($address, $retailer->id, $uid, $request->pos), request()->file('diagnostic_report'));
        Excel::import(new GobatellSalesSummaryReportImport($uid), request()->file('sales_summary_report'));

        $file1 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('diagnostic_report')->getClientOriginalName(), '.' . $request->file('diagnostic_report')->getClientOriginalExtension()) . time() . '.' . $request->diagnostic_report->extension();
        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('sales_summary_report')->getClientOriginalName(), '.' . $request->file('sales_summary_report')->getClientOriginalExtension()) . time() . '.' . $request->sales_summary_report->extension();
        $path1 = $request->diagnostic_report->move(public_path('reports/'), $file1);
        $path2 = $request->sales_summary_report->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file1, $file2, $address);

        return;
    }

    private function storeGreenline($retailer, $request, $address)
    {
        $request->validate([
            'inventory_log_summary' => 'required|file|mimes:csv,xlsx',
        ]);


        Excel::import(new GreenlineReportImport($address, $retailer->id, $request->pos), request()->file('inventory_log_summary'));

        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . time() . '.' . $request->inventory_log_summary->extension();
        $path2 = $request->inventory_log_summary->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file2, $file2, $address);

        return;
    }
    private function  storeEpos($retailer, $request, $address, $retailerReportSubmission)
    {
        $request->validate([
            'inventory_log_summary' => 'required|file|mimes:csv,xlsx',
        ]);

        Excel::import(new EposReportImport($retailerReportSubmission), request()->file('inventory_log_summary'));

        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . time() . '.' . $request->inventory_log_summary->extension();
        $path2 = $request->inventory_log_summary->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file2, $file2, $address);

        return;
    }

    private function storeTechpos($retailer, $request, $address)
    {
        $request->validate([
            'inventory_log_summary' => 'required|file|mimes:csv,xlsx',
        ]);
        Excel::import(new TechPosReportImport($address, $retailer->id, $request->pos), request()->file('inventory_log_summary'));

        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . time() . '.' . $request->inventory_log_summary->extension();

        $path2 = $request->inventory_log_summary->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file2, $file2, $address);
        return;
    }

    private function storePennylane($retailer, $request, $address)
    {
        $request->validate([
            'inventory_log_summary' => 'required|file|mimes:csv,xlsx',
        ]);

        Excel::import(new PennyLaneReportImport($address, $retailer->id, $request->pos), request()->file('inventory_log_summary'));

        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . time() . '.' . $request->inventory_log_summary->extension();

        $path2 = $request->inventory_log_summary->move(public_path('reports/'), $file2);

        $retailer = Retailer::where('id', $retailer->id)->first();

        $this->createRetalerReportSubmission($request, $retailer, $file2, $file2, $address);

        return;
    }

    private function storeDuctie($retailer, $request, $address, $uid)
    {
        Excel::import(new DuctieDiagnosticReportImport($address, $retailer->id, $uid, $request->pos), request()->file('diagnostic_report'));
        Excel::import(new DuctieSalesSummaryReportImport($uid), request()->file('sales_summary_report'));

        $file1 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('diagnostic_report')->getClientOriginalName(), '.' . $request->file('diagnostic_report')->getClientOriginalExtension()) . time() . '.' . $request->diagnostic_report->extension();
        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . '_' . basename($request->file('sales_summary_report')->getClientOriginalName(), '.' . $request->file('sales_summary_report')->getClientOriginalExtension()) . time() . '.' . $request->sales_summary_report->extension();
        $path1 = $request->diagnostic_report->move(public_path('reports/'), $file1);
        $path2 = $request->sales_summary_report->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file1, $file2, $address);

        return;
    }

    private function storeProfitech($retailer, $request, $address)
    {
        $request->validate([
            'inventory_log_summary' => 'required|file|mimes:csv,xlsx',
        ]);

        Excel::import(new ProfitTechReportImport($address, $retailer->id, $request->pos), request()->file('inventory_log_summary'));;

        $file2 = $request->pos . '_' . \Carbon\Carbon::now()->subMonth()->format('F') . time() . '.' . $request->inventory_log_summary->extension();

        $path2 = $request->inventory_log_summary->move(public_path('reports/'), $file2);

        $this->createRetalerReportSubmission($request, $retailer, $file2, $file2, $address);
        return;
    }

    private function createRetalerReportSubmission($request, $retailer, $file1, $file2, $address)
    {
        RetailerReportSubmission::where('retailer_id', $retailer->id)
            ->whereMonth('date', Carbon::now()->startOfMonth()->subMonth()->format('m'))
            ->whereYear('date', Carbon::now()->startOfMonth()->subMonth()->format('Y'))
            ->where('address_id', $address->id)->update([
                'status' => 'Submited',
                'pos' => $request->pos,
                'file1' => $file1,
                'file2' => $file2,
            ]);

        Retailer::where('id', $retailer->id)->update([
            'report_count' => $retailer->report_count + 1,
        ]);

        Activity::create([
            'activity' => $request->pos . ' Report Uploaded By ' . $retailer->user->name,
        ]);
    }

    public function monthlyReportsStatus($request, $status)
    {
        if ($request->get('report_name') != null) {
            $col_name = $request->col_name;
            if ($col_name == 'location' || $col_name == 'province' || $col_name == 'POS') {
                $reports = RetailerReportSubmission::where('status', 'Submited')
                    ->where($col_name, 'LIKE', '%' . $request->report_name . '%')
                    ->whereMonth('date', Carbon::parse($request->date_search)->format('m'))
                    ->whereYear('date', Carbon::parse($request->date_search)->format('Y'))
                    ->with('retailer.user')
                    ->whereHas('retailer', function ($q) use ($request, $col_name) {
                        return  $q->where('status', 'Approved');
                    })->orderBy('updated_at', 'DESC')->paginate(10);
            } else {
                $reports = RetailerReportSubmission::where('status', 'Submited')
                    ->with('retailer.user')
                    ->whereHas('retailer', function ($q) use ($request, $col_name) {
                        $q->where('status', 'Approved');
                        if ($col_name == 'DBA') {
                            $q->where($col_name, 'LIKE', '%' . $request->report_name . '%');
                            $q->whereMonth('date', Carbon::parse($request->date_search)->format('m'));
                            $q->whereYear('date', Carbon::parse($request->date_search)->format('Y'));
                        }
                        if ($col_name == 'name') {
                            $q->whereHas('user', function ($query) use ($request, $col_name) {
                                $query->where($col_name, 'LIKE', '%' . $request->report_name . '%');
                                $query->whereMonth('date', Carbon::parse($request->date_search)->format('m'));
                                $query->whereYear('date', Carbon::parse($request->date_search)->format('Y'));
                            });
                        }
                        return  $q;
                    })->orderBy('updated_at', 'DESC')->paginate(10);
            }
        } elseif ($request->has('date_search')) {
            $reports = RetailerReportSubmission::where('status', 'Submited')
                ->whereMonth('date', Carbon::parse($request->date_search)->format('m'))
                ->whereYear('date', Carbon::parse($request->date_search)->format('Y'))
                ->with('retailer.user')
                ->whereHas('retailer', function ($q) {
                    return $q->where('status', 'Approved');
                })->orderBy('updated_at', 'DESC')->paginate(10);
        } else {
            $reports = RetailerReportSubmission::where('status', 'Submited')
                ->whereMonth('date', Carbon::now()->startOfMonth()->subMonth()->format('m'))
                ->whereYear('date', Carbon::now()->startOfMonth()->subMonth()->format('Y'))
                ->with('retailer.user', 'address')
                ->whereHas('retailer', function ($q) {
                    return $q->where('status', 'Approved');
                })
                ->orderBy('updated_at', 'DESC')
                ->paginate(10);
        }
        return $reports;
    }

    public function showReports($request, $status)
    {
        $retailerReports = Retailer::where('id', Auth::user()->userable->id)->with(['user', 'ReportStatus' => function ($q) {
            return $q->where('status', 'Submited')->orderBy('id', 'DESC');
        }])->paginate(10);
        $date = ReportSubmissionDate::select('starting_date', 'ending_date')->where('month', Carbon::now()->format('F'))->where('year', Carbon::now()->format('Y'))->first();

        return ['retailerReports' => $retailerReports, 'date' => $date];
    }

    public function MonthlyReportByProvince()
    {
        $retailerReportSubmission = RetailerReportSubmission::Date()->first();
        $province_id = array("ON", "MB", "SK", "AB", "BC");
        $province_name = array("Ontario", "Manitoba", "Saskatchewan", "Alberta", "British Columbia");
        if ($retailerReportSubmission == null) {
            $data['retailerReport'] = null;
            $data['lpReport'] = null;
            return $data;
        }
        $data['retailerReport'] = $this->retailerMonthlyReportByProvince($province_name, $province_id, $retailerReportSubmission);
        $data['lpReport'] = $this->lpMonthlyReportByProvince($province_name, $province_id, $retailerReportSubmission);
        return $data;
    }

    private function retailerMonthlyReportByProvince($province_name, $province_id, $retailerReportSubmission)
    {
        $retailerCount = RetailerReportSubmission::where('id', '>=', $retailerReportSubmission->id)
            // ->distinct('location')
            ->count();

        $retailerReport['TotalPurchasedCost'] = RetailerStatement::where('retailerReportSubmission_id', '>=', $retailerReportSubmission->id)->sum('total_purchase_cost');

        $retailerReport['FeeInDollars'] = RetailerStatement::where('retailerReportSubmission_id', '>=', $retailerReportSubmission->id)->sum('fee_in_dollar');

        $retailerReport['CountOfRetailer'] = $retailerCount;

        return $retailerReport;
    }

    private function lpMonthlyReportByProvince($province_name, $province_id, $retailerReportSubmission)
    {
        $retailer_report_submission_id = $retailerReportSubmission->id;
        $retailerCount = RetailerStatement::where('retailerReportSubmission_id', '>=', $retailerReportSubmission->id)->distinct('retailerReportSubmission_id')->count();
        $SumOfTotalPurchasedCost = 0;
        $SumOfFeeInDollars = 0;
        for ($i = 0; $i < 5; $i++) {
            $retunVariable = str_replace(' ', '', $province_name[$i]);
            $retunVariable = strtolower($retunVariable);
            $totalPurchasedCost = 0;
            $feeInDollars = 0;

            $retailerStatements = RetailerStatement::where('retailerReportSubmission_id', '>=', $retailerReportSubmission->id)->whereHas('reportsubmissions', function ($query) use ($province_id, $province_name, $i) {
                return $query->where('province', $province_id[$i])->orWhere('province', $province_name[$i]);
            })->get();

            foreach ($retailerStatements as $retailerStatement) {
                $checkCarveout = CarveOut::where([
                    ['retailer_id', $retailerStatement->reportsubmissions->retailer_id],
                    ['lp', $retailerStatement->lps->user->name]
                ])->where(function ($q) use ($province_id, $province_name, $i) {
                    $q->where('location', "ON");
                    return $q;
                })->first();

                if (!$checkCarveout) {
                    $totalPurchasedCost = (float)$totalPurchasedCost + (float)$retailerStatement->total_purchase_cost;
                    $feeInDollars = (float)$feeInDollars + (float)$retailerStatement->fee_in_dollar;
                }
            }

            $SumOfTotalPurchasedCost = (float)$SumOfTotalPurchasedCost + (float)$totalPurchasedCost;
            $SumOfFeeInDollars = (float)$SumOfFeeInDollars + (float)$feeInDollars;
            $lpReport[$retunVariable . 'TotalPurchasedCost'] = (float) $totalPurchasedCost;
            $lpReport[$retunVariable . 'FeeInDollars'] = (float) $feeInDollars;
        }

        $lpReport['CountOfRetailer'] = $retailerCount;
        $lpReport['SumOfTotalPurchasedCost'] = (float) $SumOfTotalPurchasedCost;
        $lpReport['SumOfFeeInDollars'] = (float) $SumOfFeeInDollars;
        return $lpReport;
    }
}
