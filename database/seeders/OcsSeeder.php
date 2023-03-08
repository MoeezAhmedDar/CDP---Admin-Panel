<?php

namespace Database\Seeders;

use App\Models\OcsProvincialCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OcsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OcsProvincialCatalog::truncate();
        $csvData = fopen(public_path('provincial_catalog/catalogue.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 3000, ',')) !== false) {
            if (!$transRow) {
                DB::table('ocs_provincial_catalogs')->insert([
                    "category" => $data['0'],
                    "sub_category" => $data['1'],
                    "sub_sub_category" => $data['2'],
                    "product_name" => $data['3'],
                    "brand" => $data['4'],
                    "supplier_name" => $data['5'],
                    "product_short_description" => $data['6'],
                    "product_long_description" => $data['7'],
                    "size" => $data['8'],
                    "colour" => $data['9'],
                    "image_url" => $data['10'],
                    "unit_of_measure" => $data['11'],
                    "stock_status" => $data['12'],
                    "unit_price" => $data['13'],
                    "pack_size" => $data['14'],
                    "minimum_thc_content" => $data['15'],
                    "maximum_thc_content" => $data['16'],
                    "thc_content_per_unit" => $data['17'],
                    "thc_content_per_volume" => $data['18'],
                    "minimum_cbd_content" => $data['19'],
                    "maximum_cbd_content" => $data['20'],
                    "cbd_content_per_unit" => $data['21'],
                    "cbd_content_per_volume" => $data['22'],
                    "dried_flower_cannabis_equivalency" => $data['23'],
                    "plant_type" => $data['24'],
                    "terpenes" => $data['25'],
                    "growingmethod" => $data['26'],
                    "number_of_items_in_a_retail_pack" => $data['27'],
                    "gtin" => \Str::replaceFirst('00', '', $data['28']),
                    "ocs_item_number" => $data['29'],
                    "ocs_variant_number" => $data['30'],
                    "physical_dimension_width" => $data['31'],
                    "physical_dimension_height" => $data['32'],
                    "physical_dimension_depth" => $data['33'],
                    "physical_dimension_volume" => $data['34'],
                    "physical_dimension_weight" => $data['35'],
                    "eaches_per_inner_pack" => $data['36'],
                    "eaches_per_master_case" => $data['37'],
                    "inventory_status" => $data['38'],
                    "storage_criteria" => $data['39'],
                    "food_allergens" => $data['40'],
                    "ingredients" => $data['41'],
                    "street_name" => $data['42'],
                    "grow_medium" => $data['43'],
                    "grow_method" => $data['44'],
                    "grow_region" => $data['45'],
                    "drying_method" => $data['46'],
                    "trimming_method" => $data['47'],
                    "extraction_process" => $data['48'],
                    "carrier_oil" => $data['49'],
                    "heating_element_type" => $data['50'],
                    "battery_type" => $data['51'],
                    "rechargeable_battery" => $data['52'],
                    "removable_battery" => $data['53'],
                    "replacement_parts_available" => $data['54'],
                    "temperature_control" => $data['55'],
                    "temperature_display" => $data['56'],
                    "compatibility" => $data['57'],
                    "thc_min" => $data['58'],
                    "thc_max" => $data['59'],
                    "cbd_min" => $data['60'],
                    "cbd_max" => $data['61'],
                    "net_weight" => $data['62'],
                    "craft" => $data['63'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
