<?php

namespace App\Imports;

use App\Models\AlbertaProvincialCatalog;
use App\Models\BritishColumbiaProvincialCatalog;
use App\Models\LpFixedFeeStructure;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Lp;
use App\Models\MbllProvincialCatalog;
use App\Models\OcsProvincialCatalog;
use App\Models\SaskatchewanProvincialCatalog;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class lpFixedFeeImportuploadindividual implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $lp_id = Session::get('lp_id');
        $data = [];
        $lp = Lp::whereHas('user', function ($q) use ($lp_id) {
            $q->where('userable_id', $lp_id);
            return $q;
        })->first();

        if ($lp) {
            if ($row['province'] == 'ON' || $row['province'] == 'Ontario') {
                $data = $this->checkOntario($row['provincial_sku'], $row['gtin'], $row['product_description_size']);
            } elseif ($row['province'] == 'MB' || $row['province'] == 'Manitoba') {
                $data = $this->checkManitoba($row['provincial_sku'], $row['gtin'], $row['product_description_size']);
            } elseif ($row['province'] == 'BC' || $row['province'] == 'British Columbia') {
                $data = $this->checkBC($row['provincial_sku'], $row['gtin'], $row['product_description_size']);
            } elseif ($row['province'] == 'AB' || $row['province'] == 'Alberta') {
                $data = $this->checkAlberta($row['provincial_sku'], $row['gtin'], $row['product_description_size']);
            } elseif ($row['province'] == 'SK' || $row['province'] == 'Saskatchewan') {
                $data = $this->checkSaskatchewan($row['provincial_sku'], $row['gtin'], $row['product_description_size']);
            }

            $lpFixedFee = new LpFixedFeeStructure();
            $lpFixedFee->lp_legal_name = $row['lp_legal_name'];
            $lpFixedFee->lp_doing_business_as = $row['lp_doing_business_as'];
            $lpFixedFee->province_id = $row['province'];
            $lpFixedFee->product_description_and_size = $row['product_description_size'];
            $lpFixedFee->pre_roll = $row['pre_roll'];
            $lpFixedFee->brand = $row['brand'];
            $lpFixedFee->provincial_sku = $row['provincial_sku'];
            $lpFixedFee->gtin = $row['gtin'];
            $lpFixedFee->data_fee = $row['data_fee'];
            $lpFixedFee->cost = $row['cost'];
            $lpFixedFee->lp_id = $lp->id;
            $lpFixedFee->comment = $data['comments'];

            if ($data['provincialCatalog'] != null) {
                $lpFixedFee->flag = '0';
            } else {
                $lpFixedFee->flag = '1';
            }
            $lpFixedFee->save();
        }
        return;
    }

    private function checkOntario($provincial_sku, $gtin, $product_description_size)
    {
        $provincialCatalog = null;
        $comments = null;
        $provincialCatalog = OcsProvincialCatalog::where('ocs_variant_number', $provincial_sku)->first();

        if ($provincialCatalog == null) {
            $comments = 'Sku not found in the provincial catalog';
            $provincialCatalog = OcsProvincialCatalog::where('gtin', $gtin)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the provincial catalog';
            $provincialCatalog = OcsProvincialCatalog::where('product_name', 'like', '%' .  $product_description_size . '%')->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Product not found in the provincial catalog';
        }

        return ['comments' => $comments, 'provincialCatalog' => $provincialCatalog];
    }

    private function checkManitoba($provincial_sku, $gtin, $product_description_size)
    {
        $provincialCatalog = null;
        $comments = null;
        $provincialCatalog = MbllProvincialCatalog::where('skumbll_item_number', $provincial_sku)->first();
        if ($provincialCatalog == null) {
            $comments = 'Sku not found in the provincial catalog';
            $provincialCatalog = MbllProvincialCatalog::where('upcgtin', $gtin)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the provincial catalog';
            $provincialCatalog = MbllProvincialCatalog::where('description1', 'like', '%' .  $product_description_size . '%')->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Product not found in the provincial catalog';
        }

        return ['comments' => $comments, 'provincialCatalog' => $provincialCatalog];
    }

    private function checkBC($provincial_sku, $gtin, $product_description_size)
    {
        $provincialCatalog = null;
        $comments = null;
        $provincialCatalog = BritishColumbiaProvincialCatalog::where('sku', $provincial_sku)->first();

        if ($provincialCatalog == null) {
            $comments = 'Sku not found in the provincial catalog';
            $provincialCatalog = BritishColumbiaProvincialCatalog::where('su_code', $gtin)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the provincial catalog';
            $provincialCatalog = BritishColumbiaProvincialCatalog::where('product_name', 'like', '%' . $product_description_size . '%')->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Product not found in the provincial catalog';
        }

        return ['comments' => $comments, 'provincialCatalog' => $provincialCatalog];
    }

    private function checkAlberta($provincial_sku, $gtin, $product_description_size)
    {
        $provincialCatalog = null;
        $comments = null;
        $provincialCatalog = AlbertaProvincialCatalog::where('aglc_sku',  $provincial_sku)->first();

        if ($provincialCatalog == null) {
            $comments = 'Sku not found in the provincial catalog';
            $provincialCatalog = OcsProvincialCatalog::where('gtin', $gtin)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the provincial catalog';
            $provincialCatalog = AlbertaProvincialCatalog::where('gtin', $product_description_size)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Product not found in the provincial catalog';
        }

        return ['comments' => $comments, 'provincialCatalog' => $provincialCatalog];
    }

    private function checkSaskatchewan($provincial_sku, $gtin, $product_description_size)
    {
        $provincialCatalog = null;
        $comments = null;
        $provincialCatalog = SaskatchewanProvincialCatalog::where('sku',  $provincial_sku)->first();

        if ($provincialCatalog == null) {
            $comments = 'Sku not found in the provincial catalog';
            $provincialCatalog = OcsProvincialCatalog::where('gtin', $gtin)->first();
            $provincialCatalog = SaskatchewanProvincialCatalog::where('gtin', $gtin)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the provincial catalog';
            $provincialCatalog = SaskatchewanProvincialCatalog::where('product_name', 'like', '%' . $product_description_size . '%')->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Product not found in the provincial catalog';
        }

        return ['comments' => $comments, 'provincialCatalog' => $provincialCatalog];
    }
}
