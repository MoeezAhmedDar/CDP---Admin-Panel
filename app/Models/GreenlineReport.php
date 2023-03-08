<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GreenlineReport extends Model
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
    ];

    public function greenlineReports()
    {
        return $this->belongsToMany(GreenlineReport::class, 'greenline_retailer_report', 'retailer_id', 'greenline_report_id');
    }
}
