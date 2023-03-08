<?php

namespace App\Repositories\User;

use App\Interfaces\User\RetailerRepositoryInterface;
use App\Jobs\sendRetailerRegistrationEmailJob;
use App\Jobs\SendSetPasswordJob;
use App\Mail\changeStatusMail;
use App\Mail\sendAddRetailerNotificationEmail;
use App\Mail\sendNewMemberRegisteredEmail;
use App\Models\CanadaCities;
use App\Models\Retailer;
use App\Models\RetailerAddress;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class RetailerRepository implements RetailerRepositoryInterface
{
    public function index($status)
    {
        $count = 0;
        if (isset($_GET['retailer'])) {
            $col_name = $_GET['col_name'];

            $retailers =  Retailer::with(['user', 'RetailerAddresses'])->where(function ($query)  use ($col_name)  {
                if ($col_name == 'name')
                {
                    $query->whereHas('user', function ($q) use ($col_name) {
                        $q->where($col_name, 'LIKE', "%{$_GET['retailer']}%");
                    });
                }
                if ($col_name == 'location' || $col_name == 'province')
                {
                    $query->whereHas('RetailerAddresses', function ($k) use ($col_name) {
                        $k->where($col_name, 'LIKE', "%{$_GET['retailer']}%");
                    });
                }

                if($col_name == 'DBA')
                {
                    $query->Where($col_name, 'LIKE', "%{$_GET['retailer']}%");
                }

                return $query;
            })->where('status',  'Approved')->paginate(10);
        } else {
            $retailers = ($status) ?
                Retailer::where('status', $status)->with(['user', 'RetailerAddresses'])->orderBy('id', 'DESC')
                ->paginate(10)
                : Retailer::where('status', '!=', 'Requested')->with(['user', 'RetailerAddresses'])
                ->orderBy('id', 'DESC')
                ->paginate(10);
        }
        foreach ($retailers as $retailer) {
            $addressCount = RetailerAddress::where('retailer_id', $retailer->id)
                ->count();
            if ($addressCount > $count) {
                $count = $addressCount;
            }
        }

        return ['count' => $count, 'retailers' => $retailers];
    }

    public function store($request, $retailer)
    {
        $retailer->update([
            'corporate_name' => $request->corporate_name,
            'DBA' => $request->dba,
            'aggregated_data' => 'Yes',
            'status' => 'Pending',
        ]);
        User::where('userable_type', 'App\Models\Retailer')
            ->where('userable_id', $retailer->id)
            ->update([
                'password' => Hash::make($request->password),
                'flag' => 0
            ]);
        for ($i = 0; $i < count($request->street_number); $i++) {
            $retailerAddress = new RetailerAddress();
            $retailerAddress->street_number = $request->street_number[$i];
            $retailerAddress->street_name = $request->street_name[$i];
            $retailerAddress->postal_code = $request->postal_code[$i];
            $retailerAddress->city = $request->city[$i];
            $retailerAddress->province = $request->province[$i];
            $retailerAddress->location = $request->street_number[$i] . ' ' . $request->street_name[$i] . ' ' . $request->city[$i];
            $retailerAddress->contact_person_name_at_location
                = $request->contact_person_name_at_location[$i];
            $retailerAddress->contact_person_phone_number_at_location = $request->contact_person_phone_number_at_location[$i];

            $retailer->RetailerAddresses()->save($retailerAddress);
        }
        Mail::to($retailer->user->email)->send(new sendAddRetailerNotificationEmail());
        Mail::to('Hello@irccollective.com')->send(new sendNewMemberRegisteredEmail($request, $retailer));

        return;
    }

    public function update($request, $retailer)
    {
        $retailer->update([
            'corporate_name' => $request->corporate_name,
            'DBA' => $request->DBA,
            'owner_phone_number' => $request->owner_phone_number,
            'aggregated_data' => $request->aggregated_data,
        ]);
        User::where('userable_type', 'App\Models\Retailer')
            ->where('userable_id', $retailer->id)
            ->update([
                'name' => $request->owner_name,
            ]);
        for ($i = 0; $i < count($request->street_number); $i++) {
            RetailerAddress::where('id', $request->address_id[$i])->update([
                'street_number' => $request->street_number[$i],
                'street_name' => $request->street_name[$i],
                'postal_code' => $request->postal_code[$i],
                'location' => $request->location[$i],
                'contact_person_name_at_location'
                => $request->contact_person_name_at_location[$i],
                'contact_person_phone_number_at_location' => $request->contact_person_phone_number_at_location[$i],
            ]);
        }
        return;
    }

    public function update_Requested($request, $retailer)
    {
        $retailer->update([
            'owner_phone_number' => $request->owner_phone_number,
        ]);
        User::where('userable_type', 'App\Models\Retailer')
            ->where('userable_id', $retailer->id)
            ->update([
                'name' => $request->owner_name,
                'email' => $request->email,
                'flag' => 1
            ]);
        $user = User::where('userable_type', 'App\Models\Retailer')
            ->where('userable_id', $retailer->id)->first();
        dispatch(new sendRetailerRegistrationEmailJob($user));

        return;
    }

    public function sendEmail($user)
    {
        $user = User::where('id', $user->id)->first();
        $token = \Illuminate\Support\Str::random(64);
        $details['name'] = $user->name;
        $details['email'] = $user->email;
        $details['token'] = $token;
        dispatch(new SendSetPasswordJob($details));

        return;
    }
    public function add($request)
    {
        $retailer = Retailer::create([
            'owner_phone_number' => $request->owner_phone_number,
            'status' => 'Requested',
        ]);
        $user = new User();
        $user->name = $request->owner_first_name . ' ' . $request->owner_last_name;
        $user->email = $request->email;
        $user->flag = 1;
        $retailer->user()->save($user);
        $role = \Spatie\Permission\Models\Role::where('name', 'Retailer')
            ->first();
        $user->assignRole([$role->id]);
        dispatch(new sendRetailerRegistrationEmailJob($user));

        return;
    }

    public function search($status)
    {
        $retailers = Retailer::latest()->with(['user', 'RetailerAddresses'])->whereHas('ReportStatus', function ($q) use ($status) {
            return $q->whereMonth('date', '=', now()->format('m'))->whereYear('date', '=', now()->format('Y'))->where('status', $status);
        })->withCount('RetailerAddresses')->paginate(4);

        return $retailers;
    }

    public function address_store($request, $retailer)
    {
        for ($i = 0; $i < count($request->street_number); $i++) {

            $retailerAddress = new RetailerAddress();
            $retailerAddress->street_number = $request->street_number[$i];
            $retailerAddress->street_name = $request->street_name[$i];
            $retailerAddress->postal_code = $request->postal_code[$i];
            $retailerAddress->city = $request->city[$i];
            $retailerAddress->province = $request->province[$i];
            $retailerAddress->location = $request->street_number[$i] . ' ' . $request->street_name[$i] . ' ' . $request->city[$i];
            $retailerAddress->contact_person_name_at_location
                = $request->contact_person_name_at_location[$i];
            $retailerAddress->contact_person_phone_number_at_location = $request->contact_person_phone_number_at_location[$i];

            $retailer->RetailerAddresses()->save($retailerAddress);
        }

        return;
    }


    public function getRetailerForm($user)
    {
        $retailer  = Retailer::where('id', $user->userable_id)->first();
        return  $retailer;
    }
    public function getCanadaCities()
    {
        $provinces = CanadaCities::select('province_name')
            ->distinct()
            ->get();
        return  $provinces;
    }

    public function getRetailer($retailer)
    {
        $retailer = Retailer::where('id', $retailer->id)
            ->with(['user', 'RetailerAddresses'])
            ->first();

        return $retailer;
    }
    public function updateRetailer($request, $retailer)
    {
        if ($retailer->status != $request->status) {
            Mail::to($retailer->user->email)->send(new changeStatusMail($request->status));
        }

        $retailer->update([
            'corporate_name' => $request->corporate_name,
            'DBA' => $request->DBA,
            'owner_phone_number' => $request->owner_phone_number,
            'aggregated_data' => $request->aggregated_data,
            'status' => $request->status
        ]);

        User::where('userable_type', 'App\Models\Retailer')
            ->where('userable_id', $retailer->id)
            ->update([
                'name' => $request->owner_name,
                'email' => $request->email,
            ]);


        for ($i = 0; $i < count($request->street_number); $i++) {
            RetailerAddress::where('id', $request->address_id[$i])->update([
                'street_number' => $request->street_number[$i],
                'street_name' => $request->street_name[$i],
                'postal_code' => $request->postal_code[$i],
                'location' => $request->location[$i],
                'contact_person_name_at_location'
                => $request->contact_person_name_at_location[$i],
                'contact_person_phone_number_at_location' => $request->contact_person_phone_number_at_location[$i],
            ]);
        }
        return;
    }
}
