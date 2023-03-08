<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovaSalesSummaryReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'product',
        'sku',
        'category',
        'unit',
        'items_sold',
        'items_ref',
        'net_qty',
        'gross_sales',
        'sub_total',
        'total_Cost',
        'gross_profit',
        'gross_margin',
        'total_margin',
        'markdown_percentage',
        'average_retail_price',
        'cova_diagnostic_report_id'
    ];


    public function CovaDiagnosticReport()
    {
        $this->belongsTo(CovaDiagnosticReport::class, 'cova_diagnostic_report_id', 'id');
    }
}
