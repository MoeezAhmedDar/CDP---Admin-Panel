<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DuctieSalesSummaryReport extends Model
{
    use HasFactory;
    protected $fillable = [
        'locationname',
        'serialnumber',
        'productid',
        'sku',
        'product',
        'productcategory',
        'mastercategory',
        'unit',
        'dayssupply',
        'inventorystart',
        'coststart',
        'received',
        'costreceived',
        'transferredin',
        'costtransferredin',
        'allocated',
        'costallocated',
        'sold',
        'costsold',
        'transferredout',
        'costtransferredout',
        'returned',
        'costreturned',
        'adjup',
        'costadjup',
        'adjdown',
        'costadjdown',
        'inventoryend',
        'costend',
        'lastauditeddate',
        'dd_report_id',
        'solddate',
        'customertype',
        'quantitysold',
        'grosssales',
        'discount',
        'netsales',
        'avgpriceperunit',
        'taxapplied'

    ];

    public function DuctieDiagnosticReport()
    {
        $this->belongsTo(DuctieDiagnosticReport::class, 'dd_report_id', 'id');
    }
}
