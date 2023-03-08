<?php

namespace App\Imports;

use App\Models\Retailer;
use App\Models\User;
use App\Models\RetailerAddress;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Jobs\SendSetPasswordJob;
use App\Models\RetailerReportSubmission;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Role;

class RetailerImport implements ToModel, WithHeadingRow
{
    public function __construct()
    {
    }

    public function model(array $row)
    {
        dd($row);
        if ($row['retailer_name']) {
            $user = User::where('email', $row['email'])->first();
            if (!$user) {
                $retailer = Retailer::create([
                    'DBA' => $row['dba'],
                    'corporate_name' => $row['corporate_name'],
                    'aggregated_data' => str_replace(' ', '', $row['aggregated_data']),
                    'owner_phone_number' => $row['owner_phone_number'],
                    'status' => 'Approved'
                ]);

                $user = new User();
                $user->name = $row['retailer_name'];
                $user->email = $row['email'];
                $user->flag = 0;

                $retailer->user()->save($user);

                $retailerAddress = new RetailerAddress();
                $retailerAddress->street_number = $row['street_number'];
                $retailerAddress->street_name = $row['street_name'];
                $retailerAddress->postal_code = $row['postal_code'];
                $retailerAddress->city = $row['city'];
                $retailerAddress->province =  strlen($row['province'] == 2) ? strtoupper(trim($row['province']))  : ucwords(trim($row['province']));
                $retailerAddress->location = $row['street_number'] . ' ' . $row['street_name'] . ' ' . $row['city'];
                $retailerAddress->contact_person_name_at_location
                    = $row['contact_person_name_at_location'];
                $retailerAddress->contact_person_phone_number_at_location = $row['contact_person_phone_number_at_location'];

                $retailer->RetailerAddresses()->save($retailerAddress);

                $role = Role::where('name', 'Retailer')
                    ->first();

                $user->assignRole([$role->id]);

                $token = Str::random(64);

                DB::table('password_resets')->insert([
                    'email' => $row['email'],
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                $details['name'] = $user->name;
                $details['email'] = $row['email'];
                $details['token'] = $token;
                dispatch(new SendSetPasswordJob($details));
                Session::put('loop', '1');
                return;
            } else {
                $retailer = $user->userable;
                $retailerAddress = new RetailerAddress();
                $retailerAddress->street_number = $row['street_number'];
                $retailerAddress->street_name = $row['street_name'];
                $retailerAddress->postal_code = $row['postal_code'];
                $retailerAddress->city = $row['city'];
                $retailerAddress->province = $row['province'];
                $retailerAddress->location = $row['street_number'] . ' ' . $row['street_name'] . ' ' . $row['city'];
                $retailerAddress->contact_person_name_at_location
                    = $row['contact_person_name_at_location'];
                $retailerAddress->contact_person_phone_number_at_location = $row['contact_person_phone_number_at_location'];
                $retailerAddress->retailer_id = $retailer->id;
                $retailerAddress->save();

                return;
            }
        }
    }
}
