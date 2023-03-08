<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LpFixedFeeStructure extends Model
{
    use HasFactory;

    protected $fillable = [
        'lp_legal_name',
        'lp_doing_business_as',
        'product_description_and_size',
        'pre_roll',
        'brand',
        'provincial_sku',
        'gtin',
        'data_fee',
        'cost',
        'province_id',
        'lp_id',
        'flag',
        'comment',
    ];

    public function lps()
    {
        return $this->belongsTo(Lp::class, 'lp_id', 'id');
    }
}
