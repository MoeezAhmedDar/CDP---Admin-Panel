<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lp extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'DBA',
        'primary_contact_name',
        'primary_contact_position',
        'primary_contact_phone',
        'status',
        'variable'
    ];
    protected $dates = ['deleted_at'];

    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    public function LpAddresses()
    {
        return $this->hasOne(LpAddress::class, 'lp_id', 'id');
    }

    public function LpAddressess()
    {
        return $this->hasMany(LpAddress::class, 'lp_id', 'id');
    }
    public function LpFixedFees()
    {
        return $this->hasMany(LpFixedFeeStructure::class, 'lp_id', 'id');
    }

    public function LpVariableFees()
    {
        return $this->hasMany(LpVariableFeeStructure::class, 'lp_id', 'id');
    }
}
