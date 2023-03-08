<?php

namespace App\Interfaces\Report;

interface ReportStoreDateRepositoryInterface 
{
    public function StoreReportSubmissionDate($request);
    public function UpdateReportSubmissionDate($request);
}
