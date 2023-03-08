<?php

namespace App\Interfaces\User;

interface AdminRepositoryInterface
{
    public function storeAdmin($request);
    public function getAdminDetails($admin);
    public function updateAdmin($admin, $request);
}
