<?php

namespace App\Jobs;

use App\Models\AlbertaProvincialCatalog;
use App\Models\BritishColumbiaProvincialCatalog;
use App\Models\CarveOut;
use App\Models\CleanSheet;
use App\Models\LpFixedFeeStructure;
use App\Models\LpVariableFeeStructure;
use App\Models\MbllProvincialCatalog;
use App\Models\OcsProvincialCatalog;
use App\Models\Retailer;
use App\Models\RetailerReportSubmission;
use App\Models\RetailerStatement;
use App\Models\SaskatchewanProvincialCatalog;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class GenerateStatement implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $cleanReportRepository;
    private $id;
    private $retailer_id;

    public function __construct($id, $retailer_id, $cleanReportRepository)
    {
        $this->cleanReportRepository = $cleanReportRepository;
        $this->id = $id;
        $this->retailer_id = $retailer_id;
        // $this->handle();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $id = $this->id;
        $retailer_id = $this->retailer_id;
        try {
            DB::beginTransaction();
            $retailerReportSubmission = RetailerReportSubmission::where('id', $id)->first();
            $this->cleanReportRepository->checkPos($retailerReportSubmission, $retailer_id);
            $this->cleanReportRepository->checkRetailerStatement($retailerReportSubmission, $retailer_id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
    }
}
