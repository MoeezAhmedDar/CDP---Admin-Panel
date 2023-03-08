<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovaSalesSummaryReportRetailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'cova_sales_report_id',
        'date',
        'province',
        'location',
    ];
}
