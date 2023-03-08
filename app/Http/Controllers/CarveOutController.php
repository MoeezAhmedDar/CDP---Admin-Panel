<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarveOut\StoreRequest;
use App\Interfaces\Report\CarveOutRepositoryInterface;
use App\Models\CarveOut;
use App\Models\Lp;
use App\Models\Retailer;
use App\Traits\CanadaCities;
use App\Traits\GlobalVariables;
use App\Traits\ReturnMessage;
use Illuminate\Http\Request;

class CarveOutController extends Controller
{
    use ReturnMessage, GlobalVariables;
    private CarveOutRepositoryInterface $CarveoutRepository;

    public function __construct(CarveOutRepositoryInterface $CarveoutRepository)
    {
        $this->CarveoutRepository = $CarveoutRepository;
    }

    public function index(Retailer $retailer)
    {
        $carveout = $this->CarveoutRepository->index($retailer);

        return view('admin.retailer.carve-outs.index', [
            'provinces' => $this->getCanadaCities(),
            'lps' => $carveout['lps'],
            'retailer' => $retailer,
            'carveouts' => $carveout['carveouts']
        ]);
    }

    public function store(StoreRequest $request)
    {
        $this->CarveoutRepository->store($request);

        $messages['success'] = "Carve Out Added Successfully";
        return redirect()
            ->back()
            ->with('messages', $messages);
    }

    public function edit(StoreRequest $request)
    {
        CarveOut::where('id', $request->id)->update($request->all('lp', 'location'));

        $messages['success'] = "Carve Out Updates Sucessfully";
        return redirect()
            ->back()
            ->with('messages', $messages);
    }

    public function destroy(CarveOut $carveOut)
    {
        $carveOut->delete();

        $messages['success'] = "Carve Out deleted Successfully";
        return redirect()
            ->back()
            ->with('messages', $messages);
    }
}
