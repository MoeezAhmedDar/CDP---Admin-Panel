<?php

namespace Database\Seeders;

use App\Models\MbllProvincialCatalog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class MbllSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MbllProvincialCatalog::truncate();
        $csvData = fopen(public_path('provincial_catalog/mbll_listing.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 3000, ',')) !== false) {
            if (!$transRow) {
                MbllProvincialCatalog::create([
                    'order_qty_cases' => $data['0'],
                    'list_type' => $data['1'],
                    'skumbll_item_number' => $data['2'],
                    'upcgtin' => $data['3'],
                    'supplier' => $data['4'],
                    'brand' => $data['5'],
                    'type' => $data['6'],
                    'sub_type' => $data['7'],
                    'description1' => $data['8'],
                    'thc_range' => $data['9'],
                    'cbd_range' => $data['10'],
                    'unit_volsize' => $data['11'],
                    'unit_of_measure' => $data['12'],
                    'unit_price' => $data['13'],
                    'units_per_case' => $data['14'],
                    'case_price' => $data['15'],
                    'product_notifications' => $data['16'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
