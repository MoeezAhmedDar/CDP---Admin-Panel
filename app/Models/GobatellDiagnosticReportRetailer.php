<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GobatellDiagnosticReportRetailer extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'retailer_id',
        'gb_diagnostic_id',
        'date',
        'province',
        'location',
        'pos',

        
    ];
}
