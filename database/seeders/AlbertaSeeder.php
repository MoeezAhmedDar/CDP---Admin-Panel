<?php

namespace Database\Seeders;

use App\Models\AlbertaProvincialCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AlbertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AlbertaProvincialCatalog::truncate();
        $csvData = fopen(public_path('provincial_catalog/alberta_listing.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 3000, ',')) !== false) {
            if (!$transRow) {
                AlbertaProvincialCatalog::create([
                    'aglc_sku' => $data['0'],
                    'quantity' => $data['1'],
                    'sku_description' => $data['2'],
                    'available_cases' => $data['3'],
                    'format' => $data['4'],
                    'subcategory' => $data['5'],
                    'thc_min' => $data['6'],
                    'thc_max' => $data['7'],
                    'thc_uom' => $data['8'],
                    'cbd_min' => $data['9'],
                    'cbd_max' => $data['10'],
                    'cbd_uom' => $data['11'],
                    'eachespercase' => $data['12'],
                    'new_sku_this_week' => $data['13'],
                    'on_sale' => $data['14'],
                    'sell_price_per_case' => $data['15'],
                    'orginal_price_per_case' => $data['16'],
                    'sell_price_per_unit' => $data['17'],
                    'msrp' => $data['18'],
                    'recycle_fees_per_case' => $data['19'],
                    'deposit_fee_per_case' => $data['20'],
                    'company_name' => $data['21'],
                    'brand_name' => $data['22'],
                    'product_name' => $data['23'],
                    'long_product_description' => $data['24'],
                    'merchandising_strategy' => $data['25'],
                    'type_sub_2_category' => $data['26'],
                    'strain_sub_3_category' => $data['27'],
                    'province_of_origin' => $data['28'],
                    'region_of_product_optional' => $data['29'],
                    'extraction_process' => $data['30'],
                    'dominant_terpene_1' => $data['31'],
                    'dominant_terpene_1_content' => $data['32'],
                    'dominant_terpene_2' => $data['33'],
                    'dominant_terpene_2_content' => $data['34'],
                    'dominant_terpene_3' => $data['35'],
                    'dominant_terpene_3_content' => $data['36'],
                    'other_terpenes_list' => $data['37'],
                    'net_content' => $data['38'],
                    'content_uom' => $data['39'],
                    'piece_qty' => $data['40'],
                    'piece_size' => $data['41'],
                    'dce_g' => $data['42'],
                    'master_case_height_cm' => $data['43'],
                    'master_case_length_cm' => $data['44'],
                    'master_case_width_cm' => $data['45'],
                    'master_case_weight_kg' => $data['46'],
                    'external_packaging_material' => $data['47'],
                    'each_inner_height_cm' => $data['48'],
                    'each_inner_length_cm' => $data['49'],
                    'each_inner_width_cm' => $data['50'],
                    'each_inner_weight_grams' => $data['51'],
                    'gtin' => $data['52'],
                    'mastercasegtin' => $data['53'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
