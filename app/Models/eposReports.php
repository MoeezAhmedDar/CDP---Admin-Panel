<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class eposReports extends Model
{
    use HasFactory;
    protected $fillable = [
        'sku',
        'name',
        'barcode',
        'brand',
        'compliance_category',
        'opening',
        'sold',
        'purchased',
        'closing',
        'average_price',
        'average_cost',
        'retailerReportSubmission_id',
    ];

    public function reportsubmission()
    {
        return $this->belongsTo(RetailerReportSubmission::class, 'retailerReportSubmission_id', 'id');
    }
}
