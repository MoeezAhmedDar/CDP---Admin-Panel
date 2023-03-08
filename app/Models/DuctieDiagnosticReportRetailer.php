<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuctieDiagnosticReportRetailer extends Model
{
    use HasFactory;
    protected $fillable = [
        'retailer_id',
        'dd_report_id',
        'date',
        'province',
        'location',
        'pos',
    ];
}
