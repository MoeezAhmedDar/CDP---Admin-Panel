<?php

namespace Database\Seeders;

use App\Models\CarveOut;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarveOutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CarveOut::truncate();
        $csvData = fopen(public_path('carve_out/carveOut_sample_UPDATED.csv'), 'r');
        $transRow = true;
        while (($data = fgetcsv($csvData, 3000, ',')) !== false) {
            if (!$transRow) {
                CarveOut::create([
                    'retailer_name' => $data['0'],
                    'email' => $data['1'],
                    'carve_outs' => $data['2'],
                    'location' => $data['3'],
                    'lp' => $data['4'],
                ]);
            }
            $transRow = false;
        }
        fclose($csvData);
    }
}
