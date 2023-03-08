<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Interfaces\User\AdminRepositoryInterface;
use App\Models\Retailer;
use App\Models\User;
use App\Models\CanadaCities;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\setPasswordMail;
use App\Repositories\User\AdminRepository;
use App\Traits\ReturnMessage;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ReturnMessage;
    private AdminRepositoryInterface $adminRepository;

    public function __construct(AdminRepositoryInterface $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('user-list')) {
            return redirect()->route('dashboard');
        }

        return view(
            'admin.user.index',
            [
                'superAdmins' => SuperAdmin::with('user')->latest()
                    ->get(),
            ]
        );
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('user-create')) {
            return redirect()->route('dashboard');
        }

        return view(
            'admin.user.create',
            [
                'roles' => Role::where('id', '>', 3)
                    ->get()
            ]
        );
    }

    public function store(UserStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->adminRepository->storeAdmin($request);
            DB::commit();

            $messages['success'] = "Super Admin Added Successfully";
            return redirect()
                ->route('admins.index')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function show(SuperAdmin $admin)
    {
        if (!Auth::user()->hasPermissionTo('user-show')) {
            return redirect()->route('dashboard');
        }
        $detail = $this->adminRepository->getAdminDetails($admin);

        return view('admin.user.show', [
            'admin' => $admin,
            'roles' => $detail['roles'],
            'userRole' => $detail['userRole']
        ]);
    }

    public function edit(SuperAdmin $admin)
    {
        if (!Auth::user()->hasPermissionTo('user-edit')) {
            return redirect()->route('dashboard');
        }
        $detail = $this->adminRepository->getAdminDetails($admin);

        return view('admin.user.edit', [
            'admin' => $admin,
            'user' => $detail['user'],
            'roles' => $detail['roles'],
            'userRole' => $detail['userRole']
        ]);
    }

    public function update(UserUpdateRequest $request, SuperAdmin $admin)
    {
        try {
            DB::transaction(function () use ($request, $admin) {
                $this->adminRepository->updateAdmin($admin, $request);
            });

            $messages['success'] = "Super Admin Updated Successfully";
            return redirect()
                ->route('admins.index')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage());
        }
    }

    public function destroy(SuperAdmin $admin)
    {
        if (!Auth::user()->hasPermissionTo('user-delete')) {
            return redirect()->route('dashboard');
        }
        // $admin->user->delete();
        // $admin->delete();

        $messages['danger'] = "Super Admin deleted Successfully";
        return redirect()
            ->route('admins.index')
            ->with('messages', $messages);
    }

    public function getCities(Request $request)
    {
        $cities = CanadaCities::where('province_name', $request->province)
            ->get();

        return response()
            ->json($cities);
    }
}
