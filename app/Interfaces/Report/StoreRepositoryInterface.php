<?php

namespace App\Interfaces\Report;

interface StoreRepositoryInterface
{
    public function checkRetailerReportSubmission($retailer, $address);
    public function store($retailer, $address, $request);
    public function monthlyReportsStatus($request, $status);
    public function showReports($request, $status);
    public function monthlyReportByProvince();
}
