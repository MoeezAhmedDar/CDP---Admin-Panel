<?php

namespace App\Http\Controllers;

use App\Http\Requests\CleanSheet\UpdateRequest;
use App\Interfaces\Report\CleanSheetRepositoryInterface;
use App\Models\CleanSheet;
use App\Traits\GlobalVariables;
use Illuminate\Http\Request;
use App\Traits\ReturnMessage;

class CleanSheetController extends Controller
{
    use ReturnMessage, GlobalVariables;
    private CleanSheetRepositoryInterface $CleansheetRepository;

    public function __construct(CleanSheetRepositoryInterface $CleansheetRepository)
    {
        $this->CleansheetRepository = $CleansheetRepository;
    }

    public function dirty_rows($id, $retailer_id, Request $request)
    {
        return view('admin.clean-sheets.index', [
            'dirtyRows' => $this->CleansheetRepository->dirty_rows($id, $retailer_id, $request),
            'retailer_id' => $retailer_id,
            'id' => $id,
        ]);
    }

    public function edit($id)
    {
        return view('admin.reports.edit_dirty_rows', ['covaReport' => CleanSheet::where('id', $id)->first()]);
    }
    public function update(Request $request, $id)
    {
        $tt= $this->CleansheetRepository->update($request, $id);
        if(isset($tt['warning']))
        {
            $messages['warning'] = "Didn't Match in Provincial Catalouge";
        }
        elseif($tt['success'])
        {
            $messages['success'] = "Record Updated Sucessfully";
        }
        return redirect()->route('reports.monthly.status')->with('messages', $messages);
    }

    public function destroy($id)
    {
        $CleanSheet = CleanSheet::find($id);
        $CleanSheet->delete();

        return $this->errorMessage("Record Deleted Sucessfully");
    }
}
