<?php

namespace App\Repositories\Report;

use App\Interfaces\Report\CarveOutRepositoryInterface;
use App\Models\CanadaCities;
use App\Models\CarveOut;
use App\Models\Lp;
use App\Models\Retailer;

class CarveOutRepository implements CarveOutRepositoryInterface
{
    public function index($retailer)
    {
        $lps = Lp::with('user')->get();
        $carveouts = CarveOut::where('retailer_id', $retailer->id)->paginate(10);

        return ['lps' => $lps, 'carveouts' => $carveouts];
    }
    public function store($request)
    {
        $retailer = Retailer::where('id', $request->id)->first();

        $carveout = new CarveOut();
        $carveout->retailer_name = $retailer->user->name;
        $carveout->email = $retailer->user->email;
        $carveout->carve_outs = 'yes';
        $carveout->location = $request->location;
        $carveout->lp = $request->lp;
        $carveout->retailer_id = $retailer->id;

        $carveout->save();

        return;
    }
}
