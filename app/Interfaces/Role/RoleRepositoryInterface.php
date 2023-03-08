<?php

namespace App\Interfaces\Role;

interface RoleRepositoryInterface
{
    public function store($request);
    public function update($request, $id);
}
