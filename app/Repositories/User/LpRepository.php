<?php

namespace App\Repositories\User;

use App\Http\Requests\Lp\LpUpdateOfferRequest;
use App\Imports\lpVariableFeeImportuploadindividual;
use App\Interfaces\User\LpRepositoryInterface;
use App\Jobs\UpdateLpOfferJob;
use App\Models\CanadaCities;
use App\Models\CarveOut;
use App\Models\Lp;
use App\Models\LpAddress;
use App\Models\LpVariableFeeStructure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Session;
use Spatie\Permission\Contracts\Role;

class  LpRepository  implements LpRepositoryInterface
{
    public function store($request, $lp)
    {
        $lp->update([
            'DBA' => $request->DBA,
            'primary_contact_name' => $request->primary_contact_name,
            'primary_contact_position' => $request->primary_contact_position,
            'status' => 'Pending'
        ]);

        User::where('userable_type', 'App\Models\Lp')
            ->where('userable_id', $lp->id)
            ->update([
                'password' => Hash::make($request->password),
                'flag' => 0,
            ]);

        $lpAddress = new LpAddress();
        $lpAddress->street_number = $request->street_number;
        $lpAddress->street_name = $request->street_name;
        $lpAddress->postal_code = $request->postal_code;
        $lpAddress->city = $request->city;
        $lpAddress->province = $request->province;

        $lp->LpAddresses()->save($lpAddress);

        return;
    }

    public function index($request, $status = null)
    {
        $col_name = $request->col_name;
        $request->has('lp_name') ?

            $lps =  Lp::with(['user', 'LpAddresses'])
            ->where(function ($query) use ($request, $col_name) {
                if ($col_name == 'name') {
                    $query->whereHas('user', function ($q)  use ($request, $col_name) {
                        $q->Where($col_name, 'LIKE', "%{$request->lp_name}%");
                    });
                }
                if ($col_name == 'DBA') {
                    $query->orWhere($col_name, 'LIKE', "%{$request->lp_name}%");
                }
                return $query;
            })->paginate(10)

            :

            $lps = ($status) ?
            $lps = Lp::where('status', $status)->with(['user', 'LpAddresses'])->orderBy('id', 'DESC')
            ->paginate(10)
            :
            $lps = Lp::where('status', '!=', 'Requested')->with(['user', 'LpAddresses'])->orderBy('id', 'DESC')
            ->paginate(10);

        return $lps;
    }
    public function show($lp)
    {
        $lp = Lp::where('id', $lp->id)
            ->with(['user', 'LpAddresses', 'LpFixedFees', 'LpVariableFees'])
            ->first();

        return $lp;
    }
    public function offers($lp)
    {
        $offers = LpVariableFeeStructure::where('lp_id', $lp->id)
            ->whereMonth('created_at', now()->format('m'))
            ->whereYear('created_at', now()->format('Y'))
            ->paginate(10);

        return $offers;
    }

    public function edit($lp)
    {
        $lp = Lp::where('id', $lp->id)
            ->with(['user', 'LpAddresses', 'LpFixedFees', 'LpVariableFees'])
            ->first();

        $statuses = collect(['Pending', 'Approved', 'Rejected']);

        return ['lp' => $lp, 'statuses' => $statuses];
    }
    public function update_offer($request, $offer)
    {
        dispatch(new UpdateLpOfferJob($request->all(), $offer));
        $offer->update([
            'product_name' => $request->product_name,
            'provincial' => $request->provincial,
            'GTin' => $request->GTin,
            'unit_cost' => $request->unit_cost,
        ]);

        return;
    }
    public function update($request, $lp)
    {
        if ($lp->user->name != $request->name) {
            CarveOut::where('lp', $lp->user->name)
                ->update([
                    'lp' => $request->name,
                ]);
        }
        $lp->update([
            'DBA' => $request->DBA,
            'primary_contact_name' => $request->primary_contact_name,
            'primary_contact_position' => $request->primary_contact_position,
            'primary_contact_phone' => $request->primary_contact_phone,
            'status' => $request->status
        ]);
        User::where('userable_type', 'App\Models\Lp')
            ->where('userable_id', $lp->id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
                'userable_type' => 'App\Models\Lp',
                'userable_id' => $lp->id
            ]);
        LpAddress::where('lp_id', $lp->id)->update([
            'street_number' => $request->street_number,
            'street_name' => $request->street_name,
            'postal_code' => $request->postal_code,
            'city' => $request->city,
            'province' => $request->province,
            'lp_id' => $lp->id
        ]);

        return;
    }
    public function variable_fee_store($request, $lp)
    {
        for ($i = 0; $i < count($request->province); $i++) {
            $fee = LpVariableFeeStructure::create([
                'province' => $request->province[$i],
                'category' => $request->category[$i],
                'brand' => $request->brand[$i],
                'product_name' => $request->product_name[$i],
                'provincial' => $request->provincial[$i],
                'GTin' => $request->GTin[$i],
                'product' => $request->product[$i],
                'thc' => $request->thc[$i],
                'cbd' => $request->cbd[$i],
                'case' => $request->case[$i],
                'unit_cost' => $request->unit_cost[$i],
                'offer' => Carbon::parse($request->offer[$i])->format('Y-m-d'),
                'offer_end' => Carbon::parse($request->offer_end[$i])->format('Y-m-d'),
                'data' => $request->data[$i],
                'comments' => $request->comments[$i],
                'links' => $request->links[$i],
                'lp_id' => $lp->id
            ]);
        }

        return;
    }
    public function upload_individual_csv($request)
    {
        Excel::import(new lpVariableFeeImportuploadindividual($request->lp_id), request()->file('VariableFee'));

        return;
    }

    public function add($request)
    {
        $lp = Lp::create([
            'primary_contact_phone' => $request->primary_contact_phone,
            'status' => 'Approved',
        ]);
        $lp->update([
            'DBA' => $request->DBA,
            'primary_contact_name' => $request->primary_contact_name,
            'primary_contact_position' => $request->primary_contact_position,
            'status' => 'Pending'
        ]);

        User::where('userable_type', 'App\Models\Lp')
            ->where('userable_id', $lp->id)
            ->update([
                'password' => Hash::make($request->password),
                'flag' => 0,
            ]);

        $lpAddress = new LpAddress();
        $lpAddress->street_number = $request->street_number;
        $lpAddress->street_name = $request->street_name;
        $lpAddress->postal_code = $request->postal_code;
        $lpAddress->city = $request->city;
        $lpAddress->province = $request->province;

        $lp->LpAddresses()->save($lpAddress);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->flag = 1;
        $lp->user()->save($user);

        $role = \Spatie\Permission\Models\Role::where('name', 'Lp')
            ->first();

        $user->assignRole([$role->id]);
    }
}
