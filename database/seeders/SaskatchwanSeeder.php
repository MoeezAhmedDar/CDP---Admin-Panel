<?php

namespace Database\Seeders;

use App\Models\SaskatchewanProvincialCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SaskatchwanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SaskatchewanProvincialCatalog::truncate();
        $csvData = fopen(public_path('provincial_catalog/saskatchwan_listing.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 3000, ',')) !== false) {
            if (!$transRow) {
                SaskatchewanProvincialCatalog::create([
                    
                    'type' => $data['0'],
                    'sku' => $data['1'],
                    'producer_type' => $data['2'],
                    'origin' => $data['3'],
                    'brand' => $data['4'],
                    'product_name' => $data['5'],
                    'gtin' => $data['6'],
                    'strain' => $data['7'],
                    'size_grams' => $data['8'],
                    'thc' => $data['9'],
                    'cbd' => $data['10'],
                    'terp' => $data['11'],
                    'pack_date' => $data['12'],
                    'per_unit_cost' => $data['13'],
                    'qtycase' => $data['14'],
                    'case_price' => $data['15'],
                    'cases_available' => $data['16'],
                    'quantity_of_cases_to_order' => $data['17'],
                    'total' => $data['18'],
                    'invest_per_gram' => $data['19'],
                   
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
