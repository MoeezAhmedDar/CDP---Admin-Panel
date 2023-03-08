<?php

namespace App\Repositories\User;

use App\Interfaces\User\AdminRepositoryInterface;
use App\Mail\setPasswordMail;
use App\Models\SuperAdmin;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Stringable;

class AdminRepository  implements AdminRepositoryInterface
{
    public function storeAdmin($request)
    {
        $super_admin = SuperAdmin::create([
            'phone_number' => $request->phone_number,
            'address' => $request->address
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;

        $user->assignRole($request->input('role'));
        $super_admin->user()->save($user);

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        $details['name'] = $user->name;
        $details['email'] = $user->email;
        $details['token'] = $token;

        Mail::to($request->email)->send(new setPasswordMail($details));

        return;
    }

    public function getAdminDetails($admin)
    {
        $data['roles'] = Role::where('id', '>', 3)->get();
        $data['user'] = User::where('userable_id', $admin->id)
            ->where('userable_type', 'App\Models\SuperAdmin')
            ->first();
        $data['userRole'] = $data['user']->roles->all();

        return $data;
    }

    public function updateAdmin($admin, $request)
    {
        $admin->update([
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);

        User::where('userable_type', 'App\Models\SuperAdmin')
            ->where('userable_id', $admin->id)
            ->update([
                'name' => $request->name,
                'email' => $request->email,
            ]);

        DB::table('model_has_roles')->where('model_id', $admin->user->id)->delete();
        $admin->user->assignRole($request->input('role'));

        return;
    }
}
