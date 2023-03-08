<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdealDiagnosticReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'description',
        'opening',
        'purchases',
        'returns',
        'trans_in',
        'trans_out',
        'unit_sold',
        'write_offs',
        'closing',
        'net_sales_ex',
        'retailerReportSubmission_id',
        'variable'
    ];

    public function IdealSalesSummaryReport()
    {
        return $this->hasOne(IdealSalesSummaryReport::class, 'ideal_diagnostic_report_id', 'id');
    }

    public function reportsubmission()
    {
        return $this->belongsTo(RetailerReportSubmission::class, 'retailerReportSubmission_id', 'id');
    }
}
