<?php

namespace Database\Seeders;

use App\Models\BritishColumbiaProvincialCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BritishColumbiaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BritishColumbiaProvincialCatalog::truncate();
        $csvData = fopen(public_path('provincial_catalog/british_columbia.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 3000, ',')) !== false) {
            if (!$transRow) {
                BritishColumbiaProvincialCatalog::create([
                    'sku' => $data['0'],
                    'product_name' => $data['1'],
                    'brand_name' => $data['2'],
                    'bc_indigenous_product' => $data['3'],
                    'subcategory' => $data['4'],
                    'class' => $data['5'],
                    'origin_country' => $data['6'],
                    'origin_region' => $data['7'],
                    'origin_subregion' => $data['8'],
                    'su_qty_in_each_case' => $data['9'],
                    'su_code' => $data['10'],
                    'su_code_type' => $data['11'],
                    'case_code' => $data['12'],
                    'case_code_type' => $data['13'],
                    'su_product_net_size' => $data['14'],
                    'su_product_net_size_uom' => $data['15'],
                    'su_volume_equivalency' => $data['16'],
                    'su_volume_equivalency_uom' => $data['17'],
                    'case_weight' => $data['18'],
                    'case_weight_uom' => $data['19'],
                    'strain' => $data['20'],
                    'species' => $data['21'],
                    'per_activation_cbd_max' => $data['22'],
                    'per_activation_cbd_min' => $data['23'],
                    'per_activation_cbd_uom' => $data['24'],
                    'per_activation_thc_max' => $data['25'],
                    'per_activation_thc_min' => $data['26'],
                    'per_activation_thc_uom' => $data['27'],
                    'per_discrete_unit_cbd_max' => $data['28'],
                    'per_discrete_unit_cbd_min' => $data['29'],
                    'per_discrete_unit_cbd_uom' => $data['30'],
                    'per_discrete_unit_thc_max' => $data['31'],
                    'per_discrete_unit_thc_min' => $data['32'],
                    'per_discrete_unit_thc_uom' => $data['33'],
                    'per_retail_unit_cbd_max' => $data['34'],
                    'per_retail_unit_cbd_min' => $data['35'],
                    'per_retail_unit_cbd_uom' => $data['36'],
                    'per_retail_unit_thc_max' => $data['37'],
                    'per_retail_unit_thc_min' => $data['38'],
                    'per_retail_unit_thc_uom' => $data['39'],
                    'extraction_process' => $data['40'],
                    'packaging_material' => $data['41'],
                    'consumption_method' => $data['42'],
                    'harvesting_method' => $data['43'],
                    'growing_method' => $data['44'],
                    'terpene_1_type' => $data['45'],
                    'terpene_2_type' => $data['46'],
                    'terpene_3_type' => $data['47'],
                    'number_of_consumer_items' => $data['48'],
                    'consumer_item_size' => $data['49'],
                    'consumer_item_size_uom' => $data['50'],
                    'ecomm_short_description' => $data['51'],
                    'ecomm_long_description' => $data['52'],

                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
