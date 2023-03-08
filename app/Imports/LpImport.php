<?php

namespace App\Imports;

use App\Models\Lp;
use App\Models\User;
use App\Models\LpAddress;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Jobs\SendSetPasswordJob;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Session;

class LpImport implements ToModel, WithHeadingRow
{

    public function model(array $row)
    {
        if ($row['name']) {
            $user = User::where('email', $row['email'])->first();
            if (!$user) {
                $lp = Lp::create([
                    'DBA' => $row['dba'],
                    'primary_contact_name' => $row['primary_contact_name'],
                    'primary_contact_position' => $row['primary_contact_position'],
                    'primary_contact_phone' => $row['primary_contact_phone'],
                    'status' => 'Approved',
                    'variable' => Session::get('variable'),
                ]);

                $user = new User();
                $user->name = $row['name'];
                $user->email = $row['email'];
                $user->flag = 0;
                $lp->user()->save($user);

                $lpAddress = new LpAddress();
                $lpAddress->street_number = $row['street_number'];
                $lpAddress->street_name = $row['street_name'];
                $lpAddress->postal_code = $row['postal_code'];
                $lpAddress->city = $row['city'];
                $lpAddress->province = $row['province'];

                $lp->LpAddresses()->save($lpAddress);

                $role = Role::where('name', 'Lp')
                    ->first();

                $user->assignRole([$role->id]);

                $token = Str::random(64);

                DB::table('password_resets')->insert([
                    'email' => $row['email'],
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);

                $details['email'] = $row['email'];
                $details['name'] = $row['name'];
                $details['token'] = $token;
                // dispatch(new SendSetPasswordJob($details));
                Session::put('loop', '1');
            } else {
                $lp = $user->userable;

                $lpAddress = new LpAddress();
                $lpAddress->street_number = $row['street_number'];
                $lpAddress->street_name = $row['street_name'];
                $lpAddress->postal_code = $row['postal_code'];
                $lpAddress->city = $row['city'];
                $lpAddress->province = $row['province'];

                $lp->LpAddresses()->save($lpAddress);
            }
            return;
        }
    }
}
