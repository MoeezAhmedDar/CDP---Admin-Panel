<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechPosReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'openinventoryunits',
        'openinventoryvalue',
        'quantitypurchasedunits',
        'quantitypurchasedvalue',
        'costperunit',
        'quantitytransferinunits',
        'quantitytransferinvalue',
        'returnsfromcustomersunits',
        'returnsfromcustomersvalue',
        'otheradditionsunits',
        'otheradditionsvalue',
        'quantitysoldunits',
        'quantitysoldvalue',
        'onlinequantitysoldunits',
        'onlinequantitysoldvalue',
        'quantitytransferoutunits',
        'quantitytransferoutvalue',
        'quantitydestroyedunits',
        'quantitydestroyedvalue',
        'quantitylosttheftunits',
        'quantitylosttheftvalue',
        'returnstodistributorunits',
        'returnstodistributorvalue',
        'otherreductionsunits',
        'otherreductionsvalue',
        'closinginventoryunits',
        'closinginventoryvalue',
        'productname',
        'category',
        'categoryparent',
        'brand',
    ];
}
