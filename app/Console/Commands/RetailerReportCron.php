<?php

namespace App\Console\Commands;

use App\Models\Retailer;
use App\Models\RetailerReportSubmission;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RetailerReportCron extends Command
{
    protected $signature = 'status:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $retailers = Retailer::with('RetailerAddresses')->get();
        foreach ($retailers as $retailer) {
            foreach ($retailer->RetailerAddresses as $retailerAddress) {
                RetailerReportSubmission::create([
                    'retailer_id' => $retailer->id,
                    'status' => 'Pending',
                    'province' => $retailerAddress->province,
                    'location' => $retailerAddress->location,
                    'date' => Carbon::now()->startOfMonth()->subMonth()->format('Y-m-d')
                ]);
            }
        }
        return Command::SUCCESS;
    }
}
