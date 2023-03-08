<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechPosRetailerReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'techpos_report_id',
        'date',
        'province',
        'location',
        'pos'
    ];
}
