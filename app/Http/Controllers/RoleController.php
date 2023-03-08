<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\RoleStoreRequest;
use App\Interfaces\Role\RoleRepositoryInterface;
use App\Traits\ReturnMessage;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    use ReturnMessage;
    private RoleRepositoryInterface $rolerepository;

    public function __construct(RoleRepositoryInterface $rolerepository)
    {
        $this->rolerepository = $rolerepository;
    }

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('role-list')) {
            return redirect()->route('dashboard');
        }

        return view('admin.roles.index', ['roles' => Role::where('id', '>', 3)->latest()->get()]);
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('role-create')) {
            return redirect()->route('dashboard');
        }

        return view('admin.roles.create', ['permissions' => Permission::get()]);
    }

    public function store(RoleStoreRequest $request)
    {
        try {
            DB::beginTransaction();
            $this->rolerepository->store($request);
            DB::commit();
            $messages['success'] = "Role Created successfully";

            return redirect()->route('roles.index')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorMessage($e->getMessage());
        }
    }

    public function show($id)
    {
        if (!Auth::user()->hasPermissionTo('role-show')) {
            return redirect()->route('dashboard');
        }
        if ($id < 3) {
            return redirect()->route('dashboard');
        }

        return view('admin.roles.show', [
            'role' => Role::find($id),
            'rolePermissions' => Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
                ->where("role_has_permissions.role_id", $id)->get()
        ]);
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('role-edit')) {
            return redirect()->route('dashboard');
        }

        if ($id < 3) {
            return redirect()->route('roles.index');
        }

        return view('admin.roles.edit', [
            'role' => Role::find($id),
            'permissions' => Permission::get(),
            'rolePermissions' => DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
                ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
                ->all()
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($id < 3) {
            return redirect()->route('dashboard');
        }
        try {
            DB::beginTransaction();
            $this->rolerepository->update($request, $id);
            DB::commit();

            $messages['success'] = "Role Updated successfully";
            return redirect()->route('roles.index')
                ->with('messages', $messages);
        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorMessage($e->getMessage());
        }
    }
}
