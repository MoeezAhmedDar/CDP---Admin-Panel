<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GobatellSalesSummaryReportRetailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'gb_sales_report_id',
        'date',
        'province',
        'location',
    ];
}
