<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PennyLaneRetailerReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'penny_lane_report_id',
        'date',
        'province',
        'location',
        'pos'
    ];
}
