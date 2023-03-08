<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenlineRetailerReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'retailer_id',
        'greenline_report_id',
        'date',
        'province',
        'location',
        'pos'
    ];
}
