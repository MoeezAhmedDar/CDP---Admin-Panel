<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CovaDaignosticReportRetailer extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'cova_daignostic_id',
        'date',
        'province',
        'location',
        'pos',
    ];
}
