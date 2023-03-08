<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list',
            'role-create',
            'role-edit',
            'role-show',
            'role-delete',

            'report-list',

            'retailer-list',
            'retailer-create',
            'retailer-edit',
            'retailer-show',
            'retailer-delete',

            'lp-list',
            'lp-create',
            'lp-edit',
            'lp-show',
            'lp-delete',

            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            'user-show'
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
