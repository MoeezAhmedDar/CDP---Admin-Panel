<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerReportSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'retailer_id',
        'status',
        'date',
        'province',
        'location',
        'pos',
        'file1',
        'file2',
        'address_id'
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class, 'retailer_id', 'id');
    }

    public function address()
    {
        return $this->belongsTo(RetailerAddress::class, 'address_id', 'id');
    }

    public function scopeDate($query)
    {
        return $query->whereMonth(
            'date',
            now()->startOfMonth()->subMonth()->format('m')
        )->whereYear(
            'date',
            now()->startOfMonth()->subMonth()->format('Y')
        );
    }

    public function retailerStatements()
    {
        return $this->hasMany(RetailerStatement::class, 'retailerReportSubmission_id', 'id');
    }
}
