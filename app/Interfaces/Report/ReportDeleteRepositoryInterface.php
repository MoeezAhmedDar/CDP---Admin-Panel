<?php

namespace App\Interfaces\Report;

use App\Models\RetailerReportSubmission;

interface ReportDeleteRepositoryInterface
{
    public function deleteReport(RetailerReportSubmission $reportSubmission);
}
