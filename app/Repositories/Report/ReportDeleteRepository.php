<?php

namespace App\Repositories\Report;

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

class ReportDeleteRepository implements ReportDeleteRepositoryInterface
{
    public function deleteReport($reportSubmission)
    {

        $posdeleteReport = $reportSubmission->pos . '_deleteReport';
        $this->$posdeleteReport($reportSubmission);

        $reportSubmission->retailer->update(array('report_count', --$reportSubmission->retailer->report_count));
        $reportSubmission->delete();

        return;
    }
    private function cova_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $covaReportRetailers = CovaDaignosticReportRetailer::where('retailer_id', $reportSubmission->retailer_id)
            ->where('province', $reportSubmission->province)
            ->where('location', $reportSubmission->location)
            ->where('pos', $reportSubmission->pos)
            ->whereMonth('date', Carbon::parse($reportSubmission->date)->format('m'))
            ->whereYear('date', Carbon::parse($reportSubmission->date)->format('Y'))
            ->get();

        foreach ($covaReportRetailers as $key => $covaReportRetailer) {
            CovaSalesSummaryReport::where('cova_diagnostic_report_id', $covaReportRetailer->cova_daignostic_id)->delete();
            CovaDiagnosticReport::where('id', $covaReportRetailer->cova_daignostic_id)->delete();

            $covaReportRetailer->delete();
        }

        return;
    }
    private function gobatell_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $ReportRetailers = GobatellDiagnosticReportRetailer::where('retailer_id', $reportSubmission->retailer_id)
            ->where('province', $reportSubmission->province)
            ->where('location', $reportSubmission->location)
            ->where('pos', $reportSubmission->pos)
            ->whereMonth('date', Carbon::parse($reportSubmission->date)->format('m'))
            ->whereYear('date', Carbon::parse($reportSubmission->date)->format('Y'))
            ->get();

        foreach ($ReportRetailers as $key => $ReportRetailer) {
            GobatellSalesSummaryReport::where('gb_diagnostic_report_id', $ReportRetailer->gb_diagnostic_id)->delete();
            GobatellDiagnosticReport::where('id', $ReportRetailer->gb_diagnostic_id)->delete();

            $ReportRetailer->delete();
        }

        return;
    }
    private function ductie_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $ReportRetailers = DuctieDiagnosticReportRetailer::where('retailer_id', $reportSubmission->retailer_id)
            ->where('province', $reportSubmission->province)
            ->where('location', $reportSubmission->location)
            ->where('pos', $reportSubmission->pos)
            ->whereMonth('date', Carbon::parse($reportSubmission->date)->format('m'))
            ->whereYear('date', Carbon::parse($reportSubmission->date)->format('Y'))
            ->get();

        foreach ($ReportRetailers as $key => $ReportRetailer) {
            DuctieSalesSummaryReport::where('dd_report_id', $ReportRetailer->gb_diagnostic_id)->delete();
            DuctieDiagnosticReport::where('id', $ReportRetailer->dd_report_id)->delete();

            $ReportRetailer->delete();
        }

        return;
    }
    private function ideal_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $ReportRetailers = IdealDiagnosticReport::where('retailerReportSubmission_id', $reportSubmission->id)
            ->get();

        foreach ($ReportRetailers as $key => $ReportRetailer) {
            IdealSalesSummaryReport::where('ideal_diagnostic_report_id', $ReportRetailer->id)->delete();

            $ReportRetailer->delete();
        }

        return;
    }
    private function greenline_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $ReportRetailers = GreenlineRetailerReport::where('retailer_id', $reportSubmission->retailer_id)
            ->where('province', $reportSubmission->province)
            ->where('location', $reportSubmission->location)
            ->where('pos', $reportSubmission->pos)
            ->whereMonth('date', Carbon::parse($reportSubmission->date)->format('m'))
            ->whereYear('date', Carbon::parse($reportSubmission->date)->format('Y'))
            ->get();

        foreach ($ReportRetailers as $key => $ReportRetailer) {
            GreenlineReport::where('id', $ReportRetailer->greenline_report_id)->delete();
            $ReportRetailer->delete();
        }

        return;
    }
    private function techpos_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $ReportRetailers = TechPosRetailerReport::where('retailer_id', $reportSubmission->retailer_id)
            ->where('province', $reportSubmission->province)
            ->where('location', $reportSubmission->location)
            ->where('pos', $reportSubmission->pos)
            ->whereMonth('date', Carbon::parse($reportSubmission->date)->format('m'))
            ->whereYear('date', Carbon::parse($reportSubmission->date)->format('Y'))
            ->get();

        foreach ($ReportRetailers as $key => $ReportRetailer) {
            TechPosReport::where('id', $ReportRetailer->techpos_report_id)->delete();
            $ReportRetailer->delete();
        }

        return;
    }
    private function pennylane_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $ReportRetailers = PennyLaneRetailerReport::where('retailer_id', $reportSubmission->retailer_id)
            ->where('province', $reportSubmission->province)
            ->where('location', $reportSubmission->location)
            ->where('pos', $reportSubmission->pos)
            ->whereMonth('date', Carbon::parse($reportSubmission->date)->format('m'))
            ->whereYear('date', Carbon::parse($reportSubmission->date)->format('Y'))
            ->get();

        foreach ($ReportRetailers as $key => $ReportRetailer) {
            PennyLaneReport::where('id', $ReportRetailer->penny_lane_report_id)->delete();
            $ReportRetailer->delete();
        }

        return;
    }
    private function profittech_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        $ReportRetailers = ProfitTechRetailerReport::where('retailer_id', $reportSubmission->retailer_id)
            ->where('province', $reportSubmission->province)
            ->where('location', $reportSubmission->location)
            ->where('pos', $reportSubmission->pos)
            ->whereMonth('date', Carbon::parse($reportSubmission->date)->format('m'))
            ->whereYear('date', Carbon::parse($reportSubmission->date)->format('Y'))
            ->get();


        foreach ($ReportRetailers as $key => $ReportRetailer) {
            ProfitTechReport::where('id', $ReportRetailer->profit_tech_report_id)->delete();
            $ReportRetailer->delete();
        }

        return;
    }
    private function epos_deleteReport(RetailerReportSubmission $reportSubmission)
    {
        $this->deleteCleanSheet($reportSubmission);

        eposReports::where('retailerReportSubmission_id', $reportSubmission->id)
            ->delete();

        return;
    }
    private function deleteCleanSheet($reportSubmission)
    {
        CleanSheet::where('retailerReportSubmission_id', $reportSubmission->id)->delete();
        RetailerStatement::where('retailerReportSubmission_id', $reportSubmission->id)->delete();

        return;
    }
}
