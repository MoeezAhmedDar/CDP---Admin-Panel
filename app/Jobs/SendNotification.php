<?php

namespace App\Jobs;

use App\Http\Controllers\ReportController;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $details;
    public function __construct()
    {
        Log::info("__construct");
        $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::post('https://streamz.woss.dev/api/save_retailers_data', array(
            'retailer_id' => 1,
            'retailer_id_kustomkartel' => 222,
            'retailer_name' => 'Retailer',
            'retailer_province' => 'BC',
            'retailer_pos_name' => 'Cova',
            'clean_sheet_ulr' => 'https://streamz.woss.dev/public/novatore/clean_sheet.xlsx',
            'source_file_urls' =>
            array(
                0 =>
                array(
                    'source_file_url' => 'https://streamz.woss.dev/public/novatore/diag.xlsx',
                ),
                1 =>
                array(
                    'source_file_url' => 'https://streamz.woss.dev/public/novatore/sales.xlsx',
                ),
            ),
            'upload_date_time_stamp' => 'Mon, 05 Dec 2022 13:09:51 +0000',
        ));

        Log::info($response->body());
    }
}
