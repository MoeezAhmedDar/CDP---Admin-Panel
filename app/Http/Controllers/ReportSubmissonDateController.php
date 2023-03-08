<?php

namespace App\Http\Controllers;

use App\Http\Requests\Report\StoreReportSubmissionDate;
use App\Http\Requests\Report\UpdateReportSubmissionDate;
use App\Interfaces\Report\ReportStoreDateRepositoryInterface;
use App\Models\ReportSubmissionDate;
use App\Traits\GlobalVariables;
use App\Traits\ReturnMessage;

class ReportSubmissonDateController extends Controller
{
    use ReturnMessage, GlobalVariables;
    private ReportStoreDateRepositoryInterface $ReportStoreDateRepository;

    public function __construct(ReportStoreDateRepositoryInterface $ReportStoreDateRepository)
    {
        $this->ReportStoreDateRepository = $ReportStoreDateRepository;
    }

    public function ReportSubmissionDate()
    {
        return view('admin.reports.upload-report-date', [
            'date' => ReportSubmissionDate::all(),
        ]);
    }

    public function StoreReportSubmissionDate(StoreReportSubmissionDate $request)
    {
        $date =  $this->ReportStoreDateRepository->StoreReportSubmissionDate($request);
        if ($date) {
            return $this->errorMessage($date);
        }

        $messages['success'] = "Date Set Successfully";
        return redirect()->back()->with('messages', $messages);
    }

    public function UpdateReportSubmissionDate(UpdateReportSubmissionDate $request)
    {

        $date =  $this->ReportStoreDateRepository->UpdateReportSubmissionDate($request);

        if ($date) {
            return $this->errorMessage($date);
        }

        $messages['success'] = "Date Set Successfully";
        return redirect()->back()->with('messages', $messages);
    }

    public function DeleteReportSubmissionDate($id)
    {
        ReportSubmissionDate::where('id', $id)->delete();

        return $this->errorMessage("Deleted SuccessFully");
    }
}
