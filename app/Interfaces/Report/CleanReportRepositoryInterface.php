<?php

namespace App\Interfaces\Report;

interface CleanReportRepositoryInterface
{
    public function checkPos($retailerReportSubmission, $retailer_id);
    public function checkRetailerStatement($retailerReportSubmission, $retailer_id);
}
