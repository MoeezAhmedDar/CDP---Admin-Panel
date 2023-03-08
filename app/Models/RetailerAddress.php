<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailerAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'street_number',
        'street_name',
        'postal_code',
        'city',
        'location',
        'province',
        'contact_person_name_at_location',
        'contact_person_phone_number_at_location',
        'addressable_type',
        'addressable_id',
        'retailer_id',
        'pos'
    ];

    public function retailer()
    {
        return $this->belongsTo(Retailer::class, 'retailer_id', 'id');
    }

    public function report_submission()
    {
        return $this->hasMany(RetailerReportSubmission::class, 'address_id', 'id');
    }
}
