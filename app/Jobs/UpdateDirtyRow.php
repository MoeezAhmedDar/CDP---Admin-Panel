<?php

namespace App\Jobs;

use App\Models\RetailerReportSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Repositories\Report\CleanReportRepository;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UpdateDirtyRow implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $id;
    private $retailer_id;
    private CleanReportRepository $cleanReportRepository;

    public function __construct($id, $retailer_id)
    {
        $this->id = $id;
        $this->retailer_id = $retailer_id;
        $this->cleanReportRepository = new CleanReportRepository();
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
            $this->cleanReportRepository->checkRetailerStatement($retailerReportSubmission, $retailer_id);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            dd("inner error");
        }
    }
}
