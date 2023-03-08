<?php

namespace App\Jobs;

use App\Models\CleanSheet;
use App\Models\CovaDaignosticReportRetailer;
use App\Models\LpFixedFeeStructure;
use App\Models\LpVariableFeeStructure;
use App\Models\Retailer;
use App\Models\RetailerAddress;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class testJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lpVariables = LpFixedFeeStructure::all();

        foreach ($lpVariables as $lpVariable) {
            $data = CleanSheet::where([
                ['flag', '1'],
                ['product_name', 'like', '%' . $lpVariable->product_description_and_size],
                ['brand', 'like', '%' . $lpVariable->brand]
            ])->count();
            Log::info($lpVariable->product_description_and_size . "---" . $lpVariable->brand . "------" . $data);
        }
    }
}
