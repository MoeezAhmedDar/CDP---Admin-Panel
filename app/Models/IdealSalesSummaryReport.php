<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdealSalesSummaryReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'product_description',
        'quantity_purchased',
        'purchase_amount',
        'return_quantity',
        'amount_return',
        'ideal_diagnostic_report_id'
    ];

    public function IdealDiagnosticReport()
    {
        $this->belongsTo(IdealDiagnosticReport::class, 'ideal_diagnostic_report_id', 'id');
    }
}
