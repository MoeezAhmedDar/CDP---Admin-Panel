<?php

namespace App\Repositories\Role;

use App\Interfaces\Role\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class  RoleRepository  implements RoleRepositoryInterface
{
    public function store($request)
    {
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));

        return;
    }

    public function update($request, $id)
    {
        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();
        $role->syncPermissions($request->input('permission'));

        return;
    }
}
