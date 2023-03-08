<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarveOut extends Model
{
    use HasFactory;

    protected  $fillable = [
        'retailer_name',
        'email',
        'carve_outs',
        'location',
        'lp',
        'retailer_id'
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class, 'retailer_id', 'id');
    }
}
