<?php

namespace App\Imports;

use App\Models\LpVariableFeeStructure;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Lp;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Carbon;
use App\Models\AlbertaProvincialCatalog;
use App\Models\BritishColumbiaProvincialCatalog;
use App\Models\MbllProvincialCatalog;
use App\Models\OcsProvincialCatalog;
use App\Models\SaskatchewanProvincialCatalog;
use illuminate\Support\Str;
use PhpSpellcheck\SpellChecker\Aspell;
use PhpSpellcheck\Spellchecker\PHPPspell;


class lpVariableFeeImportuploadindividual implements ToModel, WithHeadingRow
{
    private $lp_id;

    public function __construct($lp_id)
    {
        $this->lp_id = $lp_id;
    }

    public function model(array $row)
    {
        if ($row['province']) {
            $row['province'] = strlen($row['province'] == 2) ? strtoupper(trim($row['province']))  : ucwords(trim($row['province']));
            if ($row['product_name']  ||  $row['gtin_unit'] || $row['provincial_sku']) {
                $data = [];
                $lpVariableFee = new LpVariableFeeStructure();
                if ($row['province'] == 'ON' || $row['province'] == 'Ontario') {
                    $data = $this->checkOntario($row['provincial_sku'], $row['gtin_unit'], $row['product_name']);
                } elseif ($row['province'] == 'MB' || $row['province'] == 'Manitoba') {
                    $data = $this->checkManitoba($row['provincial_sku'], $row['gtin_unit'], $row['product_name']);
                } elseif ($row['province'] == 'BC' || $row['province'] == 'British Columbia') {
                    $data = $this->checkBC($row['provincial_sku'], $row['gtin_unit'], $row['product_name']);
                } elseif ($row['province'] == 'AB' || $row['province'] == 'Alberta') {
                    $data = $this->checkAlberta($row['provincial_sku'], $row['gtin_unit'], $row['product_name']);
                } elseif ($row['province'] == 'SK' || $row['province'] == 'Saskatchewan') {
                    $data = $this->checkSaskatchewan($row['provincial_sku'], $row['gtin_unit'], $row['product_name']);
                } else {
                    return;
                }
                $lpVariableFee = new LpVariableFeeStructure();
                $lpVariableFee->lp = $row['lp'];
                $lpVariableFee->province = $row['province'];
                $lpVariableFee->category = $row['category'];
                $lpVariableFee->brand = $row['brand'];
                $lpVariableFee->product_name = $row['product_name'] ? $row['product_name'] : '*';
                $lpVariableFee->provincial = $row['provincial_sku'] ? $row['provincial_sku'] : '*';
                $lpVariableFee->GTin = $row['gtin_unit'] ? $row['gtin_unit'] : '*';
                $lpVariableFee->product = $row['product_size_g'];
                $lpVariableFee->thc = $row['thc_range'];
                $lpVariableFee->cbd = $row['cbd_range'];
                $lpVariableFee->case = $row['case_quantity_units_per_case'];
                $lpVariableFee->unit_cost = $row['unit_cost_excl_hst'];
                $lpVariableFee->offer = Carbon::parse($row['offer_start'])->format('Y-m-d');
                $lpVariableFee->offer_end = Carbon::parse($row['offer_end'])->format('Y-m-d');
                $lpVariableFee->data = $row['data_fee'];
                $lpVariableFee->comments = $row['comment'];
                $lpVariableFee->links = $row['link_to_product_and_brand_info'];
                $lpVariableFee->lp_id = $this->lp_id;
                $lpVariableFee->comments = $data['comments'];
                if ($data['provincialCatalog'] != null) {
                    $lpVariableFee->flag = '0';
                } else {
                    $lpVariableFee->flag = '1';
                }
                $lpVariableFee->save();

                return;
            }
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
            $provincialCatalog = AlbertaProvincialCatalog::where('gtin', $gtin)->first();
        }
        if ($provincialCatalog == null) {
            $comments = $comments . ', Barcode not found in the provincial catalog';
            $provincialCatalog = AlbertaProvincialCatalog::where('product_name', $product_description_size)->first();
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
