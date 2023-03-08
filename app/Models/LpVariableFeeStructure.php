<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpVariableFeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'lp',
        'province',
        'category',
        'brand',
        'product_name',
        'provincial',
        'GTin',
        'product',
        'thc',
        'cbd',
        'case',
        'unit_cost',
        'offer',
        'offer_end',
        'data',
        'comments',
        'links',
        'lp_id',
        'flag',
        'comment',
    ];

    public function lps()
    {
        return $this->belongsTo(Lp::class, 'lp_id', 'id');
    }

    public function scopeProvince($query, $province_id, $province_name)
    {
        return $query->where('province', $province_id)
            ->orWhere('province', $province_name);
    }

    public function scopeDate($query, $retailerReportSubmission)
    {
        return $query->whereMonth('created_at', Carbon::parse($retailerReportSubmission->created_at)->format('m'))->whereYear('created_at', Carbon::parse($retailerReportSubmission->created_at)->format('Y'));
    }
}
