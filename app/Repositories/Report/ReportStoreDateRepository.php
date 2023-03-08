<?php

namespace App\Repositories\Report;

use App\Imports\CovaDiagnosticReportImport;
use App\Imports\CovaSalesSummaryReportImport;
use App\Imports\DuctieDiagnosticReportImport;
use App\Imports\DuctieSalesSummaryReportImport;
use App\Imports\GobatellDiagnosticReportImport;
use App\Imports\GobatellSalesSummaryReportImport;
use App\Imports\GreenlineReportImport;
use App\Imports\PennyLaneReportImport;
use App\Imports\ProfitTechReportImport;
use App\Imports\TechPosReportImport;
use App\Interfaces\Report\ReportStoreDateRepositoryInterface;
use App\Interfaces\Report\StoreRepositoryInterface;
use App\Models\Activity;
use App\Models\ReportSubmissionDate;
use App\Models\Retailer;
use App\Models\RetailerReportSubmission;
use App\Traits\ReturnMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PhpParser\Node\Expr\FuncCall;

class ReportStoreDateRepository implements ReportStoreDateRepositoryInterface
{
    use ReturnMessage;

    public function StoreReportSubmissionDate($request)
    {
        $collection = $request->except(['_token']);
        $collection['month'] = Carbon::parse($collection['starting_date'])->format('F');
        $collection['year'] = Carbon::parse($collection['starting_date'])->format('Y');
        $start = Carbon::parse($collection['starting_date']);
        $end = Carbon::parse($collection['ending_date']);

        if ($start->gt($end)) {
            return $date = "Starting Date must be Less then Ending";
        }
        if (ReportSubmissionDate::where('month', $collection['month'])->where('year', $collection['year'])->first()) {
            return $date = "Date is Set for this month";
        }

        ReportSubmissionDate::where('month', $collection['month'])
            ->where('year', $collection['year'])->updateOrCreate($collection);
        return;
    }
    public function UpdateReportSubmissionDate($request)
    {
        $collection = $request->except(['_token']);
        $collection['month'] = Carbon::parse($collection['starting_date'])->format('F');
        $collection['year'] = Carbon::parse($collection['starting_date'])->format('Y');
        $start = Carbon::parse($collection['starting_date']);
        $end = Carbon::parse($collection['ending_date']);

        if ($start->gt($end)) {
            return $date = "Starting Date must be Less then Ending";
        }
        if (ReportSubmissionDate::where('month', $collection['month'])->where('year', $collection['year'])->first()) {
            return $date = "Date is Set for this month";
        }

        ReportSubmissionDate::where('id', $request->date_id)->update([
            'starting_date' => $collection['starting_date'],
            'ending_date' => $collection['ending_date'],
            'month' => Carbon::parse($collection['starting_date'])->format('F'),
            'year' => Carbon::parse($collection['starting_date'])->format('Y'),
        ]);

        return;
    }
}
