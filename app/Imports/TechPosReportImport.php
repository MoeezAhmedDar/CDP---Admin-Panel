<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\TechPosReport;
use App\Models\TechPosRetailerReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TechPosReportImport implements ToModel, WithHeadingRow
{
    private $address, $retailer_id, $pos;
    public function __construct($address, $retailer_id, $pos)
    {
        $this->address = $address;
        $this->retailer_id = $retailer_id;
        $this->pos = $pos;
    }

    public function model(array $row)
    {
        $techPos = TechPosReport::create([
            'sku' => $row['sku'],
            'openinventoryunits' => $row['openinventoryunits'],
            'openinventoryvalue' => $row['openinventoryvalue'],
            'quantitypurchasedunits' => $row['quantitypurchasedunits'],
            'quantitypurchasedvalue' => $row['quantitypurchasedvalue'],
            'costperunit' => $row['costperunit'],
            'quantitytransferinunits' => $row['quantitytransferinunits'],
            'quantitytransferinvalue' => $row['quantitytransferinvalue'],
            'returnsfromcustomersunits' => $row['returnsfromcustomersunits'],
            'returnsfromcustomersvalue' => $row['returnsfromcustomersvalue'],
            'otheradditionsunits' => $row['otheradditionsunits'],
            'otheradditionsvalue' => $row['otheradditionsvalue'],
            'quantitysoldunits' => $row['quantitysoldinstoreunits'],
            'quantitysoldvalue' => $row['quantitysoldinstorevalue'],
            'onlinequantitysoldunits' => $row['quantitysoldonlineunits'],
            'onlinequantitysoldvalue' => $row['quantitysoldonlinevalue'],
            'quantitytransferoutunits' => $row['quantitytransferoutunits'],
            'quantitytransferoutvalue' => $row['quantitytransferoutvalue'],
            'quantitydestroyedunits' => $row['quantitydestroyedunits'],
            'quantitydestroyedvalue' => $row['quantitydestroyedvalue'],
            'quantitylosttheftunits' => $row['quantitylosttheftunits'],
            'quantitylosttheftvalue' => $row['quantitylosttheftvalue'],
            'returnstodistributorunits' => $row['returnstodistributorunits'],
            'returnstodistributorvalue' => $row['returnstodistributorvalue'],
            'otherreductionsunits' => $row['otherreductionsunits'],
            'otherreductionsvalue' => $row['otherreductionsvalue'],
            'closinginventoryunits' => $row['closinginventoryunits'],
            'closinginventoryvalue' => $row['closinginventoryvalue'],
            'productname' => $row['productname'],
            'category' => $row['category'],
            'categoryparent' => $row['categoryparent'],
            'brand' => $row['brand'],
        ]);

        TechPosRetailerReport::create([
            'retailer_id' => $this->retailer_id,
            'techpos_report_id' => $techPos->id,
            'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d'),
            'location' => $this->address->location,
            'province' => $this->address->province,
            'pos' => $this->pos
        ]);

        return;
    }
}
