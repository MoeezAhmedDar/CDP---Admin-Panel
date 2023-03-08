<?php

namespace App\Http\Controllers;

use App\Interfaces\Report\ReportDeleteRepositoryInterface;
use App\Models\CleanSheet;
use App\Models\CovaDaignosticReportRetailer;
use App\Models\CovaDiagnosticReport;
use App\Models\CovaSalesSummaryReport;
use App\Models\DuctieDiagnosticReport;
use App\Models\DuctieDiagnosticReportRetailer;
use App\Models\DuctieSalesSummaryReport;
use App\Models\eposReports;
use App\Models\GobatellDiagnosticReport;
use App\Models\GobatellDiagnosticReportRetailer;
use App\Models\GobatellSalesSummaryReport;
use App\Models\GreenlineReport;
use App\Models\GreenlineRetailerReport;
use App\Models\IdealDiagnosticReport;
use App\Models\IdealSalesSummaryReport;
use App\Models\PennyLaneReport;
use App\Models\PennyLaneRetailerReport;
use App\Models\ProfitTechReport;
use App\Models\ProfitTechRetailerReport;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use App\Models\TechPosReport;
use App\Models\TechPosRetailerReport;
use App\Traits\ReturnMessage;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DeleteReportController extends Controller
{
    use ReturnMessage;
    private ReportDeleteRepositoryInterface $ReportDelete;

    public function __construct(ReportDeleteRepositoryInterface $ReportDelete)
    {
        $this->ReportDelete = $ReportDelete;
    }
    public function deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->ReportDelete->deleteReport($reportSubmission);

        $messages['success'] = "Report Deleted Successfully";
        return redirect()
            ->back()
            ->with('messages', $messages);
    }

}